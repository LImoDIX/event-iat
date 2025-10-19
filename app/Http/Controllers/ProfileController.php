<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\VisitorProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class ProfileController
 *
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 */
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $routeName = $request->route()->getName();
        
        if ($routeName === 'profile.visitor' && $user->role === 'visitor') {
            $page = 'Visitor/Profile';
        } elseif (($user->role === 'admin' || $user->role === 'organizer')) {
            $page = 'Profile/AdminOrganizer';
        } else {
            $page = 'Profile/Edit';
        }
        
        if ($page === 'Visitor/Profile') {
            $registrations = \App\Models\EventRegistration::with('event.organizer')
                ->where('user_id', $user->id)
                ->paginate(5, ['*'], 'registrations_page');
                
            $attendances = \App\Models\EventAttendance::with('event')
                ->where('user_id', $user->id)
                ->whereNotNull('attended_at')
                ->paginate(5, ['*'], 'attendances_page');
                
            return Inertia::render($page, [
                'mustVerifyEmail' => $user instanceof MustVerifyEmail,
                'status' => session('status'),
                'registrations' => $registrations,
                'attendances' => $attendances,
            ]);
        }
        
        return Inertia::render($page, [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $routeName = $request->route()->getName();
        
        // Для администраторов и организаторов разрешаем только обновление фото
        if ($user->role === 'admin' || $user->role === 'organizer') {
            $validated = $request->validate([
                'profile_photo' => ['nullable', 'file', 'image', 'max:2048'], // 2MB максимум
            ]);
            
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                $user->profile_photo_path = $photoPath;
                $user->save();
            }
        } elseif ($routeName === 'profile.visitor.update' && $user->role === 'visitor') {
            // Для посетителей также разрешаем только обновление фото
            $validated = $request->validate([
                'profile_photo' => ['nullable', 'file', 'image', 'max:2048'], // 2MB максимум
            ]);
            
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                $user->profile_photo_path = $photoPath;
                $user->save();
            }
        } else {
            $validated = app(ProfileUpdateRequest::class)->validated();
            
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                $validated['profile_photo_path'] = $photoPath;
            }

            $request->user()->fill($validated);

            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }

            $request->user()->save();
        }

        if ($routeName === 'profile.visitor.update' && $user->role === 'visitor') {
            return Redirect::route('profile.visitor');
        } elseif ($user->role === 'admin' || $user->role === 'organizer') {
            return Redirect::route('profile.admin-organizer');
        } else {
            return Redirect::route('profile.edit');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
