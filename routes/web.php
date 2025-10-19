<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $isLoggedIn = Auth::check();
    
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Маршруты для дешбордов с проверкой ролей
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/admin', [EventController::class, 'adminDashboard'])
        ->middleware('role:admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/organizer', [EventController::class, 'organizerDashboard'])
        ->middleware('role:organizer')
        ->name('dashboard.organizer');

    Route::get('/dashboard/visitor', [EventController::class, 'visitorDashboard'])
        ->middleware('role:visitor')
        ->name('dashboard.visitor');
});

// Маршруты для администратора
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Маршруты для управления новостями
    Route::get('/admin/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('admin.news.index');
    Route::get('/admin/news/create', [\App\Http\Controllers\NewsController::class, 'create'])->name('admin.news.create');
    Route::post('/admin/news', [\App\Http\Controllers\NewsController::class, 'store'])->name('admin.news.store');
    Route::get('/admin/news/{news}/edit', [\App\Http\Controllers\NewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('/admin/news/{news}', [\App\Http\Controllers\NewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/admin/news/{news}', [\App\Http\Controllers\NewsController::class, 'destroy'])->name('admin.news.destroy');
    
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users/{user}/toggle-block', [UserController::class, 'toggleBlock'])->name('admin.users.toggle-block');
    
    // Маршруты для управления мероприятиями администратором
    Route::get('/admin/events', [EventController::class, 'adminEvents'])->name('admin.events.index');
    Route::delete('/admin/events/{event}', [EventController::class, 'destroy'])->name('admin.events.destroy');
    Route::get('/admin/events/{event}/report', [EventController::class, 'adminReport'])->name('admin.events.report');
});

// Профиль
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Отдельные маршруты для профилей по ролям
    Route::get('/profile/visitor', [ProfileController::class, 'edit'])->name('profile.visitor');
    Route::patch('/profile/visitor', [ProfileController::class, 'update'])->name('profile.visitor.update');
    Route::get('/profile/admin-organizer', [ProfileController::class, 'edit'])->name('profile.admin-organizer');
    Route::patch('/profile/admin-organizer', [ProfileController::class, 'update'])->name('profile.admin-organizer.update');
});

// Маршруты для мероприятий
Route::middleware(['auth'])->group(function () {
    // Мероприятия для посетителей
    Route::get('/events', [EventController::class, 'index'])->name('visitor.events.index');
    Route::get('/events/search', [EventController::class, 'search'])->name('visitor.events.search');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('visitor.events.show');
    Route::get('/events/{event}/confirm-registration', [EventController::class, 'confirmRegistration'])->name('visitor.events.confirm-registration');
    Route::post('/events/{event}/confirm-registration', [EventController::class, 'processRegistration'])->name('visitor.events.process-registration');
    Route::get('/my-events', [EventController::class, 'myEvents'])->name('visitor.my-events');
    Route::delete('/events/{event}/cancel-registration', [EventController::class, 'cancelRegistration'])->name('visitor.events.cancel-registration');
    Route::get('/history', [EventController::class, 'history'])->name('visitor.history');
    Route::get('/history/{event}', [EventController::class, 'historyShow'])->name('visitor.history.show');
    Route::get('/events/{event}/rate', [EventController::class, 'rate'])->name('visitor.events.rate');
    Route::post('/events/{event}/rate', [EventController::class, 'submitRating'])->name('visitor.events.submit-rating');
    
    // Просмотр новостей
    Route::get('/news/{news}', [\App\Http\Controllers\NewsController::class, 'show'])->name('news.show');
    
    // Мероприятия для организаторов
    Route::get('/organizer/events', [EventController::class, 'organizerEvents'])->name('organizer.events.index');
    Route::get('/organizer/events/create', [EventController::class, 'create'])->name('organizer.events.create');
    Route::post('/organizer/events', [EventController::class, 'store'])->name('organizer.events.store');
    Route::delete('/organizer/events/{event}', [EventController::class, 'destroy'])->name('organizer.events.destroy');
    Route::get('/organizer/events/{event}/mark-attendance', [EventController::class, 'markAttendance'])->name('organizer.events.mark-attendance');
    Route::post('/organizer/events/{event}/registrations/{registration}/attendance', [EventController::class, 'updateAttendance'])->name('organizer.events.update-attendance');
    Route::post('/organizer/events/{event}/complete', [EventController::class, 'complete'])->name('organizer.events.complete');
    Route::get('/organizer/events/{event}/report', [EventController::class, 'report'])->name('organizer.events.report');
    Route::get('/organizer/events/statistics', [EventController::class, 'statistics'])->name('organizer.events.statistics');
});

Route::get('/test', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Laravel server is running!',
        'timestamp' => now()->toDateTimeString()
    ]);
});

require __DIR__.'/auth.php';
