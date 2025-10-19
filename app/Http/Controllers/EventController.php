<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\EventRating;

/**
 * Class EventController
 *
 * @property string|null $photo_url
 * @property bool $user_registered
 * @property string|null $user_registration_code
 * @property int $registrations_count
 * @property int $attendances_count
 * @property bool $has_rating
 * @property int $likes_count
 * @property int $dislikes_count
 * @property int $attendees_count
 */
class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index()
    {
        // For visitors, show all events (no approval needed) and not completed events
        $events = Event::with('organizer')->where('is_completed', false)->paginate(10);
        
        // Получаем регистрации пользователя
        $userRegistrations = EventRegistration::where('user_id', Auth::id())
            ->pluck('confirmation_code', 'event_id')
            ->toArray();
        
        $events->getCollection()->transform(function ($event) use ($userRegistrations) {
            if ($event->photo_path) {
                $event->photo_url = asset('storage/' . $event->photo_path);
            }
            
            if ($event->organizer && $event->organizer->profile_photo_path) {
                $event->organizer->profile_photo_url = asset('storage/' . $event->organizer->profile_photo_path);
            }
            
            // Добавляем информацию о регистрации
            $event->user_registered = isset($userRegistrations[$event->id]);
            if ($event->user_registered) {
                $event->user_registration_code = $userRegistrations[$event->id];
            }
            
            return $event;
        });
        
        return inertia('Visitor/Events/Index', [
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        // Only organizers can create events
        return inertia('Organizer/Events/Create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'duration' => 'required|integer|min:1',
            'max_attendees' => 'required|integer|min:1',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Максимум 2MB
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('event-photos', 'public');
        }

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'photo_path' => $photoPath,
            'start_time' => $request->start_time,
            'duration' => $request->duration,
            'max_attendees' => $request->max_attendees,
            'organizer_id' => Auth::id(),
            'is_approved' => true, // Мероприятия автоматически одобрены
        ]);

        return redirect()->route('organizer.events.index')
            ->with('success', 'Мероприятие успешно создано.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        // All events can be viewed by visitors (no approval needed)
        
        // Load organizer information
        $event->load('organizer');
        
        // Проверяем, зарегистрирован ли пользователь на мероприятие
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($event->photo_path) {
            $event->photo_url = asset('storage/' . $event->photo_path);
        }
        
        // Добавляем информацию о регистрации в объект мероприятия
        $event->user_registered = $registration ? true : false;
        if ($registration) {
            $event->user_registration_code = $registration->confirmation_code;
        }
        
        return inertia('Visitor/Events/Show', [
            'event' => $event,
        ]);
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        // Only the organizer who created the event can edit it
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }
        
        return inertia('Organizer/Events/Edit', [
            'event' => $event,
        ]);
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        // Only the organizer who created the event can update it
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'duration' => 'required|integer|min:1',
            'max_attendees' => 'required|integer|min:1',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Максимум 2MB
        ]);

        // Обработка загрузки фотографии
        $photoPath = $event->photo_path; // Сохраняем текущий путь к фото
        if ($request->hasFile('photo')) {
            // Удаляем старое фото, если оно было
            if ($event->photo_path) {
                Storage::disk('public')->delete($event->photo_path);
            }
            // Загружаем новое фото
            $photoPath = $request->file('photo')->store('event-photos', 'public');
        }

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'photo_path' => $photoPath,
            'start_time' => $request->start_time,
            'duration' => $request->duration,
            'max_attendees' => $request->max_attendees,
        ]);

        return redirect()->route('organizer.events.index')
            ->with('success', 'Мероприятие успешно обновлено.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Only the organizer who created the event or admin can delete it
        if ($event->organizer_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $event->delete();

        return redirect()->route('organizer.events.index')
            ->with('success', 'Мероприятие успешно удалено.');
    }

    /**
     * Show the registration confirmation page.
     */
    public function confirmRegistration(Event $event)
    {
        // Visitors can register for all events (no approval needed)
        
        // Load organizer information
        $event->load('organizer');
        
        if ($event->photo_path) {
            $event->photo_url = asset('storage/' . $event->photo_path);
        }
        
        return inertia('Visitor/Events/Confirm', [
            'event' => $event,
            'user' => Auth::user(),
        ]);
    }

    /**
     * Process the event registration after confirmation.
     */
    public function processRegistration(Request $request, Event $event)
    {
        // No approval check needed for registration
        
        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existingRegistration) {
            return redirect()->route('dashboard.visitor')->with('error', 'Вы уже зарегистрированы на это мероприятие.');
        }
        
        $registrationCount = EventRegistration::where('event_id', $event->id)->count();
        if ($registrationCount >= $event->max_attendees) {
            return redirect()->route('dashboard.visitor')->with('error', 'Мероприятие уже полностью заполнено.');
        }
        
        $confirmationCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        
        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'confirmation_code' => $confirmationCode,
        ]);
        
        return redirect()->route('dashboard.visitor')
            ->with('success', 'Вы успешно зарегистрировались на мероприятие. Ваш код подтверждения: ' . $confirmationCode);
    }

    /**
     * Display the authenticated user's registered events.
     */
    public function myEvents()
    {
        $registrations = EventRegistration::with('event.organizer')
            ->where('user_id', Auth::id())
            ->paginate(10);
            
        $registrations->getCollection()->transform(function ($registration) {
            if ($registration->event && $registration->event->photo_path) {
                $registration->event->photo_url = asset('storage/' . $registration->event->photo_path);
            }
            
            // Добавляем информацию о регистрации
            $registration->event->user_registered = true;
            $registration->event->user_registration_code = $registration->confirmation_code;
            
            return $registration;
        });
            
        return inertia('Visitor/MyEvents', [
            'registrations' => $registrations,
        ]);
    }

    /**
     * Cancel registration for an event.
     */
    public function cancelRegistration(Event $event)
    {
        // Find the registration
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$registration) {
            return redirect()->back()->with('error', 'Вы не зарегистрированы на это мероприятие.');
        }
        
        // Check if the event has already started or completed
        if (now() >= $event->start_time) {
            return redirect()->back()->with('error', 'Невозможно отменить регистрацию на мероприятие, которое уже началось или завершено.');
        }
        
        // Delete the registration
        $registration->delete();
        
        return redirect()->back()->with('success', 'Регистрация на мероприятие успешно отменена.');
    }

    /**
     * Display the authenticated user's event history.
     */
    public function history()
    {
        $attendances = EventAttendance::with('event')
            ->where('user_id', Auth::id())
            ->whereNotNull('attended_at')
            ->paginate(10);
            
        $attendances->getCollection()->transform(function ($attendance) {
            if ($attendance->event && $attendance->event->photo_path) {
                $attendance->event->photo_url = asset('storage/' . $attendance->event->photo_path);
            }
            
            // Проверяем, оставил ли пользователь оценку для этого мероприятия
            $attendance->has_rating = EventRating::where('user_id', Auth::id())
                ->where('event_id', $attendance->event->id)
                ->exists();
                
            return $attendance;
        });
            
        return inertia('Visitor/History', [
            'attendances' => $attendances,
        ]);
    }

    /**
     * Display the authenticated user's event history details.
     */
    public function historyShow(Event $event)
    {
        // Check if user attended this event
        $attendance = EventAttendance::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->whereNotNull('attended_at')
            ->first();
            
        if (!$attendance) {
            abort(404);
        }
        
        // Load event information
        $event->load('organizer');
        
        if ($event->photo_path) {
            $event->photo_url = asset('storage/' . $event->photo_path);
        }
        
        return inertia('Visitor/History/Show', [
            'event' => $event,
            'attendance' => $attendance,
        ]);
    }

    /**
     * Display the organizer's events.
     */
    public function organizerEvents()
    {
        $events = Event::with('registrations', 'attendances')
            ->where('organizer_id', Auth::id())
            ->paginate(10);
            
        $events->getCollection()->transform(function ($event) {
            if ($event->photo_path) {
                $event->photo_url = asset('storage/' . $event->photo_path);
            }
            
            // Преобразуем путь к фотографии организатора
            if ($event->organizer && $event->organizer->profile_photo_path) {
                $event->organizer->profile_photo_url = asset('storage/' . $event->organizer->profile_photo_path);
            }
            
            // Добавляем информацию о регистрации для каждого мероприятия
            $event->registrations_count = $event->registrations->count();
            $event->attendances_count = $event->attendances->count();
            
            return $event;
        });
            
        return inertia('Organizer/Events/Index', [
            'events' => $events,
        ]);
    }

    /**
     * Show the attendance marking page for an event.
     */
    public function markAttendance(Event $event)
    {
        // Only the organizer who created the event can mark attendance
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }
        
        // No approval check needed for marking attendance
        
        $registrations = EventRegistration::with('user')
            ->where('event_id', $event->id)
            ->get();
            
        // Get attendance status for each registration
        $attendanceStatus = [];
        foreach ($registrations as $registration) {
            $attendance = EventAttendance::where('registered_event_id', $registration->id)->first();
            $attendanceStatus[$registration->id] = $attendance ? !is_null($attendance->attended_at) : false;
        }
            
        if ($event->photo_path) {
            $event->photo_url = asset('storage/' . $event->photo_path);
        }
            
        return inertia('Organizer/Events/MarkAttendance', [
            'event' => $event,
            'registrations' => $registrations,
            'attendanceStatus' => $attendanceStatus,
        ]);
    }

    /**
     * Update attendance status for a registered user.
     */
    public function updateAttendance(Request $request, Event $event, EventRegistration $registration)
    {
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }
        
        // No approval check needed for updating attendance
        
        if ($registration->event_id !== $event->id) {
            abort(404);
        }
        
        $request->validate([
            'attended' => 'required|boolean',
        ]);
        
        $attendance = EventAttendance::firstOrCreate(
            ['registered_event_id' => $registration->id],
            [
                'event_id' => $event->id,
                'user_id' => $registration->user_id,
            ]
        );
        
        if ($request->attended) {
            $attendance->attended_at = now();
        } else {
            $attendance->attended_at = null;
        }
        $attendance->save();
        
        return redirect()->back()->with('success', 'Статус посещения обновлен.');
    }

    /**
     * Complete an event.
     */
    public function complete(Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }
        
        if ($event->is_completed) {
            return redirect()->back()->with('error', 'Мероприятие уже завершено.');
        }
        
        $event->is_completed = true;
        $event->save();
        
        return redirect()->back()->with('success', 'Мероприятие успешно завершено.');
    }

    /**
     * Display event report.
     */
    public function report(Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }
        
        if (!$event->is_completed) {
            return redirect()->back()->with('error', 'Отчет доступен только для завершенных мероприятий.');
        }
        
        $event->load(['registrations.user', 'attendances']);
        
        $attendedUsers = $event->attendances->pluck('user_id')->toArray();
        
        $attendedRegistrations = $event->registrations->filter(function ($registration) use ($attendedUsers) {
            return in_array($registration->user_id, $attendedUsers);
        });
        
        $nonAttendedRegistrations = $event->registrations->filter(function ($registration) use ($attendedUsers) {
            return !in_array($registration->user_id, $attendedUsers);
        });
        
        if ($event->photo_path) {
            $event->photo_url = asset('storage/' . $event->photo_path);
        }
        
        foreach ($attendedRegistrations as $registration) {
            if ($registration->user && $registration->user->profile_photo_path) {
                $registration->user->profile_photo_url = asset('storage/' . $registration->user->profile_photo_path);
            }
        }
        
        foreach ($nonAttendedRegistrations as $registration) {
            if ($registration->user && $registration->user->profile_photo_path) {
                $registration->user->profile_photo_url = asset('storage/' . $registration->user->profile_photo_path);
            }
        }
        
        $likesCount = $event->ratings->where('rating', 1)->count();
        $dislikesCount = $event->ratings->where('rating', -1)->count();
        
        return inertia('Organizer/Events/Report', [
            'event' => $event,
            'attendedRegistrations' => $attendedRegistrations,
            'nonAttendedRegistrations' => $nonAttendedRegistrations,
            'likesCount' => $likesCount,
            'dislikesCount' => $dislikesCount,
        ]);
    }

    /**
     * Search for events by code or name.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->route('visitor.events.index');
        }
        
        $events = Event::with('organizer')
            ->where('is_completed', false)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('category', 'like', '%' . $query . '%');
            })
            ->paginate(10);
            
        // Получаем регистрации пользователя
        $userRegistrations = EventRegistration::where('user_id', Auth::id())
            ->pluck('confirmation_code', 'event_id')
            ->toArray();
            
        $events->getCollection()->transform(function ($event) use ($userRegistrations) {
            if ($event->photo_path) {
                $event->photo_url = asset('storage/' . $event->photo_path);
            }
            
            if ($event->organizer && $event->organizer->profile_photo_path) {
                $event->organizer->profile_photo_url = asset('storage/' . $event->organizer->profile_photo_path);
            }
            
            // Добавляем информацию о регистрации
            $event->user_registered = isset($userRegistrations[$event->id]);
            if ($event->user_registered) {
                $event->user_registration_code = $userRegistrations[$event->id];
            }
            
            return $event;
        });
        
        return inertia('Visitor/Events/Index', [
            'events' => $events,
            'query' => $query,
        ]);
    }

    /**
     * Display the admin dashboard.
     */
    public function adminDashboard()
    {
        $totalUsers = User::count();
        $totalEvents = Event::count();
        $pendingEvents = 0; // Все мероприятия автоматически одобрены
        $blockedUsers = User::where('is_blocked', true)->count();
        
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        
        $recentEvents = Event::with('organizer')->orderBy('created_at', 'desc')->limit(5)->get()
            ->map(function ($event) {
                if ($event->photo_path) {
                    $event->photo_url = asset('storage/' . $event->photo_path);
                }
                return $event;
            });
        
        // Получаем активные новости для отображения на дашборде
        $activeNews = app(\App\Http\Controllers\NewsController::class)->getActiveNews();
        
        return inertia('Admin/Dashboard', [
            'stats' => [
                'totalUsers' => $totalUsers,
                'totalEvents' => $totalEvents,
                'pendingEvents' => $pendingEvents,
                'blockedUsers' => $blockedUsers,
            ],
            'recentUsers' => $recentUsers,
            'recentEvents' => $recentEvents,
            'news' => $activeNews,
        ]);
    }

    /**
     * Display all events for admin management.
     */
    public function adminEvents(Request $request)
    {
        $search = $request->input('search');
        
        $eventsQuery = Event::with('organizer', 'registrations', 'attendances');
        
        if ($search) {
            $eventsQuery->where('name', 'like', '%' . $search . '%');
        }
        
        $events = $eventsQuery->paginate(10);
            
        $events->getCollection()->transform(function ($event) {
            $event->registrations_count = $event->registrations->count();
            $event->attendances_count = $event->attendances->count();
            if ($event->photo_path) {
                $event->photo_url = asset('storage/' . $event->photo_path);
            }
            return $event;
        });
        
        return inertia('Admin/Events/Index', [
            'events' => $events,
        ]);
    }
    
    /**
     * Display event report for admin.
     */
    public function adminReport(Event $event)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $event->load(['registrations.user', 'attendances']);
        
        $attendedUsers = $event->attendances->pluck('user_id')->toArray();
        
        $attendedRegistrations = $event->registrations->filter(function ($registration) use ($attendedUsers) {
            return in_array($registration->user_id, $attendedUsers);
        });
        
        $nonAttendedRegistrations = $event->registrations->filter(function ($registration) use ($attendedUsers) {
            return !in_array($registration->user_id, $attendedUsers);
        });
        
        if ($event->photo_path) {
            $event->photo_url = asset('storage/' . $event->photo_path);
        }
        
        foreach ($attendedRegistrations as $registration) {
            if ($registration->user && $registration->user->profile_photo_path) {
                $registration->user->profile_photo_url = asset('storage/' . $registration->user->profile_photo_path);
            }
        }
        
        foreach ($nonAttendedRegistrations as $registration) {
            if ($registration->user && $registration->user->profile_photo_path) {
                $registration->user->profile_photo_url = asset('storage/' . $registration->user->profile_photo_path);
            }
        }
        
        $likesCount = $event->ratings->where('rating', 1)->count();
        $dislikesCount = $event->ratings->where('rating', -1)->count();
        
        // Добавляем информацию о количестве регистраций и посещений
        $event->registrations_count = $event->registrations->count();
        $event->attendances_count = $event->attendances->count();
        
        return inertia('Admin/Events/Report', [
            'event' => $event,
            'attendedRegistrations' => $attendedRegistrations,
            'nonAttendedRegistrations' => $nonAttendedRegistrations,
            'likesCount' => $likesCount,
            'dislikesCount' => $dislikesCount,
        ]);
    }

    /**
     * Display the organizer dashboard.
     */
    public function organizerDashboard()
    {
        $events = Event::with('registrations', 'attendances')
            ->where('organizer_id', Auth::id())
            ->paginate(10);
            
        $events->getCollection()->transform(function ($event) {
            if ($event->photo_path) {
                $event->photo_url = asset('storage/' . $event->photo_path);
            }
            return $event;
        });
            
        // Получаем активные новости для отображения на дашборде
        $activeNews = app(\App\Http\Controllers\NewsController::class)->getActiveNews();
        
        return inertia('Organizer/Dashboard', [
            'events' => $events,
            'news' => $activeNews,
        ]);
    }

    /**
     * Display the visitor dashboard.
     */
    public function visitorDashboard(Request $request)
    {
        $searchQuery = $request->input('query');
        
        $eventsQuery = Event::with('organizer')->where('is_completed', false);
        
        if ($searchQuery) {
            $eventsQuery->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('category', 'like', '%' . $searchQuery . '%');
            });
        }
        
        $events = $eventsQuery->paginate(10);
        
        $events->getCollection()->transform(function ($event) {
            if ($event->photo_path) {
                $event->photo_url = asset('storage/' . $event->photo_path);
            }
            return $event;
        });
        
        $registrations = EventRegistration::with('event.organizer')
            ->where('user_id', Auth::id())
            ->whereHas('event', function($query) {
                $query->where('is_completed', false);
            })
            ->paginate(10);
            
        $registrations->getCollection()->transform(function ($registration) {
            if ($registration->event && $registration->event->photo_path) {
                $registration->event->photo_url = asset('storage/' . $registration->event->photo_path);
            }
            
            if ($registration->event && $registration->event->organizer && $registration->event->organizer->profile_photo_path) {
                $registration->event->organizer->profile_photo_url = asset('storage/' . $registration->event->organizer->profile_photo_path);
            }
            
            // Добавляем информацию о регистрации
            $registration->event->user_registered = true;
            $registration->event->user_registration_code = $registration->confirmation_code;
            
            return $registration;
        });
            
        $attendances = EventAttendance::with('event')
            ->where('user_id', Auth::id())
            ->whereNotNull('attended_at')
            ->paginate(10);
            
        $attendances->getCollection()->transform(function ($attendance) {
            if ($attendance->event && $attendance->event->photo_path) {
                $attendance->event->photo_url = asset('storage/' . $attendance->event->photo_path);
            }
            return $attendance;
        });
            
        // Получаем активные новости для отображения на дашборде
        $activeNews = app(\App\Http\Controllers\NewsController::class)->getActiveNews();
        
        return inertia('Visitor/Dashboard', [
            'events' => $events,
            'registrations' => $registrations,
            'attendances' => $attendances,
            'query' => $searchQuery,
            'news' => $activeNews,
        ]);
    }
    
    /**
     * Display statistics for organizer's completed events.
     */
    public function statistics()
    {
        $events = Event::with(['registrations', 'attendances', 'ratings'])
            ->where('organizer_id', Auth::id())
            ->where('is_completed', true)
            ->paginate(10);
            
        $events->getCollection()->transform(function ($event) {
            if ($event->photo_path) {
                $event->photo_url = asset('storage/' . $event->photo_path);
            }
            
            $event->likes_count = $event->ratings->where('rating', 1)->count();
            $event->dislikes_count = $event->ratings->where('rating', -1)->count();
            $event->attendees_count = $event->attendances->count();
            
            return $event;
        });
            
        return inertia('Organizer/Events/Statistics', [
            'events' => $events,
        ]);
    }
    
    /**
     * Show the event rating page.
     */
    public function rate(Event $event)
    {
        $attendance = EventAttendance::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->whereNotNull('attended_at')
            ->first();
            
        if (!$attendance) {
            abort(403, 'Вы не участвовали в этом мероприятии.');
        }
        
        $existingRating = EventRating::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();
            
        if ($existingRating) {
            return redirect()->route('visitor.history')->with('error', 'Вы уже оценили это мероприятие.');
        }
        
        $event->load('organizer');
        
        if ($event->photo_path) {
            $event->photo_url = asset('storage/' . $event->photo_path);
        }
        
        return inertia('Visitor/Events/Rate', [
            'event' => $event,
        ]);
    }
    
    /**
     * Submit the event rating.
     */
    public function submitRating(Request $request, Event $event)
    {
        $attendance = EventAttendance::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->whereNotNull('attended_at')
            ->first();
            
        if (!$attendance) {
            abort(403, 'Вы не участвовали в этом мероприятии.');
        }
        
        $existingRating = EventRating::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();
            
        if ($existingRating) {
            return redirect()->route('visitor.history')->with('error', 'Вы уже оценили это мероприятие.');
        }
        
        $request->validate([
            'rating' => 'required|integer|in:1,-1',
        ]);
        
        EventRating::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
        ]);
        
        return redirect()->route('visitor.history')->with('success', 'Спасибо за вашу оценку!');
    }
}