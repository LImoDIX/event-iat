<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TelegramBotController extends Controller
{
    private $botToken;
    private $apiUrl;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}/";
    }

    public function webhook(Request $request)
    {
        // Для целей отладки: если это GET запрос, возвращаем информацию
        if ($request->isMethod('get')) {
            return response()->json([
                'status' => 'Webhook is working',
                'method' => 'GET',
                'message' => 'For testing webhook, please send POST request with Telegram update data',
                'timestamp' => now()->toDateTimeString()
            ]);
        }
        
        $update = $request->all();

        if (isset($update['message'])) {
            $this->handleMessage($update['message']);
        } elseif (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query']);
        }

        return response()->json(['ok' => true]);
    }

    public function setWebhook(Request $request)
    {
        $url = $request->get('url', url('/api/telegram/webhook'));
        
        $response = $this->makeRequest($this->apiUrl . 'setWebhook', [
            'url' => $url
        ]);
        
        return response()->json($response);
    }

    public function getWebhookInfo()
    {
        $response = $this->makeRequest($this->apiUrl . 'getWebhookInfo', []);
        
        return response()->json($response);
    }

    private function handleMessage($message)
    {
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        $userState = $this->getUserState($chatId);

        switch ($text) {
            case '/start':
                $this->sendWelcome($chatId);
                break;
            case '📅 Мои мероприятия':
                $this->showOrganizerEvents($chatId);
                break;
            case '⚙️ Профиль':
                $this->showProfile($chatId);
                break;
            case '🚪 Выйти':
                $this->logout($chatId);
                break;
            default:
                if ($userState === 'awaiting_username') {
                    $this->handleUsername($chatId, $text);
                } elseif ($userState === 'awaiting_password') {
                    $this->handlePassword($chatId, $text);
                } else {
                    $this->sendMessage($chatId, "Используйте /start, чтобы начать 😄");
                }
                break;
        }
    }

    private function handleCallbackQuery($callbackQuery)
    {
        $chatId = $callbackQuery['message']['chat']['id'];
        $messageId = $callbackQuery['message']['message_id'];
        $data = $callbackQuery['data'];

        $this->answerCallbackQuery($callbackQuery['id']);

        if (strpos($data, 'event_') === 0) {
            $eventId = str_replace('event_', '', $data);
            $this->showEventRegistrations($chatId, $eventId, $messageId);
        } elseif (strpos($data, 'toggle_') === 0) {
            [$prefix, $registrationId, $eventId] = explode('_', $data);
            $this->toggleAttendance($chatId, $registrationId, $eventId, $messageId);
        } elseif ($data === 'back_to_events') {
            $this->showOrganizerEvents($chatId, $messageId);
        }
    }

    private function sendWelcome($chatId)
    {
        $this->clearUserState($chatId);
        $this->setUserState($chatId, 'awaiting_username');

        $text = "👋 Привет! Я — твой помощник EventFlow.\n\n"
              . "Здесь ты можешь управлять своими мероприятиями, отмечать посетителей и следить за активностью.\n\n"
              . "Давай начнём! ✨\n"
              . "Введите ваш логин, чтобы войти в систему:";

        $this->sendMessage($chatId, $text);
    }

    private function handleUsername($chatId, $username)
    {
        $this->setUserData($chatId, 'username', $username);
        $this->setUserState($chatId, 'awaiting_password');

        $this->sendMessage($chatId, "✅ Логин принят: {$username}\n\nТеперь введите пароль 🔒");
    }

    private function handlePassword($chatId, $password)
    {
        $username = $this->getUserData($chatId, 'username');
        $user = User::where('username', $username)->first();

        if (!$user) {
            $this->clearUserState($chatId);
            $this->sendMessage($chatId, "❌ Пользователь {$username} не найден.\nПопробуйте снова с помощью /start");
            return;
        }

        if ($user->role !== User::ROLE_ORGANIZER) {
            $this->clearUserState($chatId);
            $this->sendMessage($chatId, "🚫 У вас нет доступа к функциям организатора.");
            return;
        }

        if (!Hash::check($password, $user->password)) {
            $this->clearUserState($chatId);
            $this->sendMessage($chatId, "❌ Неверный пароль. Введите /start, чтобы попробовать снова.");
            return;
        }

        if ($user->is_blocked) {
            $this->clearUserState($chatId);
            $this->sendMessage($chatId, "⛔ Ваш аккаунт заблокирован. Обратитесь к администратору.");
            return;
        }

        $this->setUserData($chatId, 'user_id', $user->id);
        $this->setUserState($chatId, 'authenticated');

        $text = "🎉 Отлично, вход выполнен!\n\n"
              . "Добро пожаловать, {$user->name} 👋\n"
              . "Теперь вы можете управлять своими мероприятиями прямо здесь.";

        $this->sendMessageWithMenu($chatId, $text);
    }

    private function showOrganizerEvents($chatId, $messageId = null)
    {
        $userId = $this->getUserData($chatId, 'user_id');
        if (!$userId) {
            $this->sendMessage($chatId, "Сессия истекла. Введите /start для входа.");
            return;
        }

        $events = Event::where('organizer_id', $userId)
            ->where('is_completed', false)
            ->orderBy('start_time', 'asc')
            ->get();

        if ($events->isEmpty()) {
            $this->editOrSend($chatId, $messageId, "📋 У вас нет активных мероприятий 😌");
            return;
        }

        $text = "📅 Ваши активные мероприятия:\n\nВыберите одно, чтобы отметить посетителей:";

        $keyboard = [];
        foreach ($events as $event) {
            $registered = EventRegistration::where('event_id', $event->id)->count();
            $attended = EventAttendance::where('event_id', $event->id)
                ->whereNotNull('attended_at')
                ->count();

            $startTime = \Carbon\Carbon::parse($event->start_time)->format('d.m.Y H:i');
            $buttonText = "{$event->name} • {$startTime}\n👥 {$attended}/{$registered}";
            $keyboard[] = [['text' => $buttonText, 'callback_data' => "event_{$event->id}"]];
        }

        $this->editOrSend($chatId, $messageId, $text, $keyboard);
    }

    private function showProfile($chatId)
    {
        $userId = $this->getUserData($chatId, 'user_id');
        $user = $userId ? User::find($userId) : null;

        if (!$user) {
            $this->sendMessage($chatId, "Сессия истекла. Введите /start для входа.");
            return;
        }

        $text = "👤 Профиль\n\n"
              . "Имя: {$user->name}\n"
              . "Логин: {$user->username}\n"
              . "Роль: Организатор\n\n"
              . "Последний вход: " . now()->format('d.m.Y H:i');

        $this->sendMessageWithMenu($chatId, $text);
    }

    private function logout($chatId)
    {
        $this->clearUserState($chatId);
        $this->sendMessage($chatId, "🚪 Вы вышли из системы.\nВведите /start, чтобы войти снова.");
    }

    private function sendMessageWithMenu($chatId, $text)
    {
        $keyboard = [
            [['text' => '📅 Мои мероприятия']],
            [['text' => '⚙️ Профиль']],
            [['text' => '🚪 Выйти']],
        ];

        $this->makeRequest($this->apiUrl . 'sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_markup' => json_encode(['keyboard' => $keyboard, 'resize_keyboard' => true])
        ]);
    }

    private function editOrSend($chatId, $messageId, $text, $keyboard = null)
    {
        if ($messageId) {
            $this->editMessage($chatId, $messageId, $text, $keyboard);
        } else {
            $this->sendMessageWithKeyboard($chatId, $text, $keyboard);
        }
    }

    private function sendMessage($chatId, $text)
    {
        $this->makeRequest($this->apiUrl . 'sendMessage', [
            'chat_id' => $chatId,
            'text' => $text
        ]);
    }

    private function sendMessageWithKeyboard($chatId, $text, $keyboard)
    {
        $this->makeRequest($this->apiUrl . 'sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_markup' => json_encode(['inline_keyboard' => $keyboard])
        ]);
    }

    private function editMessage($chatId, $messageId, $text, $keyboard = null)
    {
        $data = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text
        ];
        if ($keyboard) {
            $data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard]);
        }
        $this->makeRequest($this->apiUrl . 'editMessageText', $data);
    }

    private function answerCallbackQuery($id)
    {
        $this->makeRequest($this->apiUrl . 'answerCallbackQuery', ['callback_query_id' => $id]);
    }

    private function makeRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    private function getUserState($chatId) { return cache()->get("telegram_state_{$chatId}"); }
    private function setUserState($chatId, $state) { cache()->put("telegram_state_{$chatId}", $state, 86400); }
    private function clearUserState($chatId) {
        cache()->forget("telegram_state_{$chatId}");
        cache()->forget("telegram_data_{$chatId}");
    }
    private function getUserData($chatId, $key) {
        $data = cache()->get("telegram_data_{$chatId}", []);
        return $data[$key] ?? null;
    }
    private function setUserData($chatId, $key, $value) {
        $data = cache()->get("telegram_data_{$chatId}", []);
        $data[$key] = $value;
        cache()->put("telegram_data_{$chatId}", $data, 86400);
    }

    private function showEventRegistrations($chatId, $eventId, $messageId = null)
    {
        $userId = $this->getUserData($chatId, 'user_id');
        if (!$userId) {
            $this->sendMessage($chatId, "Сессия истекла. Введите /start для входа.");
            return;
        }

        // Проверяем, что мероприятие существует и принадлежит пользователю
        $event = Event::where('id', $eventId)
            ->where('organizer_id', $userId)
            ->first();

        if (!$event) {
            $this->sendMessage($chatId, "Мероприятие не найдено или у вас нет к нему доступа.");
            return;
        }

        // Получаем регистрации на мероприятие
        $registrations = EventRegistration::where('event_id', $eventId)
            ->with('user')
            ->get();

        if ($registrations->isEmpty()) {
            $text = "📋 На мероприятие \"{$event->name}\" ещё никто не зарегистрировался.";
            $keyboard = [
                [['text' => '⬅️ Назад', 'callback_data' => 'back_to_events']]
            ];
            $this->editOrSend($chatId, $messageId, $text, $keyboard);
            return;
        }

        // Формируем текст сообщения
        $text = "📋 Участники мероприятия \"{$event->name}\":\n\n";
        
        // Создаем клавиатуру с участниками и кнопками отметки посещения
        $keyboard = [];
        foreach ($registrations as $registration) {
            $user = $registration->user;
            if (!$user) continue;
            
            // Проверяем, был ли отмечен как посетивший
            $attendance = EventAttendance::where('event_id', $eventId)
                ->where('user_id', $user->id)
                ->first();
            
            $attended = $attendance && $attendance->attended_at ? '✅' : '❌';
            $userName = $user->name ?? $user->username ?? 'Неизвестный пользователь';
            
            $text .= "{$attended} {$userName}\n";
            $keyboard[] = [['text' => ($attended === '✅' ? 'Отменить ✅' : 'Отметить ❌') . " {$userName}",
                             'callback_data' => "toggle_{$registration->id}_{$eventId}"]];
        }
        
        $keyboard[] = [['text' => '⬅️ Назад', 'callback_data' => 'back_to_events']];
        
        $this->editOrSend($chatId, $messageId, $text, $keyboard);
    }

    private function toggleAttendance($chatId, $registrationId, $eventId, $messageId = null)
    {
        $userId = $this->getUserData($chatId, 'user_id');
        if (!$userId) {
            $this->sendMessage($chatId, "Сессия истекла. Введите /start для входа.");
            return;
        }

        // Проверяем, что мероприятие существует и принадлежит пользователю
        $event = Event::where('id', $eventId)
            ->where('organizer_id', $userId)
            ->first();

        if (!$event) {
            $this->sendMessage($chatId, "Мероприятие не найдено или у вас нет к нему доступа.");
            return;
        }

        // Получаем регистрацию
        $registration = EventRegistration::where('id', $registrationId)
            ->where('event_id', $eventId)
            ->first();

        if (!$registration) {
            $this->sendMessage($chatId, "Регистрация не найдена.");
            return;
        }

        // Получаем или создаем запись о посещении
        $attendance = EventAttendance::where('event_id', $eventId)
            ->where('user_id', $registration->user_id)
            ->first();

        if ($attendance && $attendance->attended_at) {
            // Отменяем отметку о посещении
            $attendance->attended_at = null;
            $attendance->save();
        } else {
            // Отмечаем как посетившего
            if (!$attendance) {
                $attendance = new EventAttendance();
                $attendance->event_id = $eventId;
                $attendance->user_id = $registration->user_id;
            }
            $attendance->attended_at = now();
            $attendance->save();
        }

        // Обновляем отображение списка участников
        $this->showEventRegistrations($chatId, $eventId, $messageId);
    }
}
