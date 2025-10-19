# Настройка Telegram бота с Pinggy

## Что такое Pinggy?

Pinggy - это сервис для создания публичного туннеля к вашему локальному серверу. Работает через SSH, как Serveo, но более стабильный.

## Шаг 1: Запустите Laravel сервер

\`\`\`bash
php artisan serve
\`\`\`

Убедитесь что сервер запущен на порту 8000. Вы должны увидеть:
\`\`\`
INFO  Server running on [http://127.0.0.1:8000]
\`\`\`

## Шаг 2: Проверьте что Laravel работает

Откройте в браузере: `http://localhost:8000/test`

Должны увидеть JSON ответ:
\`\`\`json
{
  "status": "ok",
  "message": "Laravel is working!",
  "timestamp": "..."
}
\`\`\`

## Шаг 3: Запустите Pinggy туннель

**Вариант 1: Без регистрации (временный URL)**

\`\`\`bash
ssh -p 443 -R0:localhost:8000 a.pinggy.io
\`\`\`

**Вариант 2: С регистрацией (постоянный URL)**

1. Зарегистрируйтесь на https://pinggy.io
2. Получите токен
3. Запустите:

\`\`\`bash
ssh -p 443 -R0:localhost:8000 -t <ваш-токен>@a.pinggy.io
\`\`\`

## Шаг 4: Получите публичный URL

После запуска Pinggy вы увидите что-то вроде:

\`\`\`
You can access local server via following URL(s):
https://randomstring.a.pinggy.link
\`\`\`

**Скопируйте этот URL!**

## Шаг 5: Обновите .env файл

Откройте `.env` и измените:

\`\`\`env
APP_URL=https://randomstring.a.pinggy.link
TELEGRAM_BOT_TOKEN=8470678650:AAGSNmrA4UvkpOtEKqxeOVT2uanUQqcbFj4
\`\`\`

## Шаг 6: Перезапустите Laravel

\`\`\`bash
# Остановите старый сервер (Ctrl+C в терминале с php artisan serve)
# Запустите заново:
php artisan serve
\`\`\`

## Шаг 7: Проверьте доступность через Pinggy

Откройте в браузере (замените на ваш URL):

\`\`\`
https://randomstring.a.pinggy.link/test
\`\`\`

Должны увидеть тот же JSON ответ. Если видите - всё работает!

## Шаг 8: Установите Telegram webhook

\`\`\`bash
php artisan telegram:set-webhook https://randomstring.a.pinggy.link/api/telegram/webhook
\`\`\`

Должны увидеть:
\`\`\`
✅ Webhook set successfully!
\`\`\`

## Шаг 9: Проверьте статус webhook

\`\`\`bash
php artisan telegram:webhook-info
\`\`\`

Должны увидеть:
\`\`\`
URL: https://randomstring.a.pinggy.link/api/telegram/webhook
Pending update count: 0
\`\`\`

Если `Pending update count: 0` - всё отлично!

## Шаг 10: Тестируйте бота

1. Откройте Telegram
2. Найдите вашего бота
3. Напишите `/start`
4. Бот должен ответить с просьбой ввести логин

## Отладка

### Смотрите логи Laravel:

\`\`\`bash
tail -f storage/logs/laravel.log
\`\`\`

### Смотрите логи Pinggy:

В терминале где запущен Pinggy вы увидите все входящие запросы.

### Проверьте что все работает:

\`\`\`bash
# 1. Laravel работает локально?
curl http://localhost:8000/test

# 2. Pinggy туннель работает?
curl https://ваш-url.a.pinggy.link/test

# 3. API endpoint доступен?
curl https://ваш-url.a.pinggy.link/api/test

# 4. Webhook установлен?
php artisan telegram:webhook-info
\`\`\`

## Важные замечания

1. **Каждый раз при перезапуске Pinggy** (без токена) вы получите новый URL
2. **Нужно будет обновить** `APP_URL` в `.env`
3. **Нужно будет переустановить** webhook командой `telegram:set-webhook`
4. **С токеном Pinggy** URL остается постоянным

## Преимущества Pinggy

- ✅ Более стабильный чем Serveo
- ✅ Показывает все входящие запросы в терминале
- ✅ Поддерживает HTTPS из коробки
- ✅ Можно получить постоянный URL с регистрацией
- ✅ Бесплатный для разработки

## Структура команд

\`\`\`bash
# Терминал 1: Laravel
php artisan serve

# Терминал 2: Pinggy
ssh -p 443 -R0:localhost:8000 a.pinggy.io

# Терминал 3: Логи (опционально)
tail -f storage/logs/laravel.log
\`\`\`

## Готово!

Теперь организаторы могут использовать Telegram бота для отметки посетителей на мероприятиях!

## Решение проблемы с Mixed Content

Если вы сталкиваетесь с ошибкой "Mixed Content" при работе через Pinggy, это происходит потому что Laravel генерирует URL с HTTP схемой вместо HTTPS.

Решение уже реализовано в приложении - добавлен специальный middleware `ForceHttps`, который автоматически устанавливает HTTPS схему для всех запросов через Pinggy.

Если вы по-прежнему сталкиваетесь с проблемой:
1. Убедитесь что вы используете URL вида `*.pinggy.link`
2. Перезапустите Laravel сервер и туннель Pinggy
