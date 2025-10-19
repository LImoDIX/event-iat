<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        // Only admin can access this
        $users = User::paginate(10);
        
        return inertia('Admin/Users/Index', [
            'users' => $users,
            'authUser' => Auth::user(),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return inertia('Admin/Users/Create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,organizer,visitor',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно создан.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Prevent editing the current user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Вы не можете редактировать самого себя.');
        }
        
        return inertia('Admin/Users/Edit', [
            'user' => $user,
            'authUser' => Auth::user(),
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role' => 'required|in:admin,organizer,visitor',
        ]);

        $user->update($request->only(['name', 'username', 'role']));

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно обновлен.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current user
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Вы не можете удалить самого себя.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно удален.');
    }

    /**
     * Block/unblock the specified user.
     */
    public function toggleBlock(User $user)
    {
        // Prevent blocking the current user
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Вы не можете заблокировать самого себя.');
        }

        $user->is_blocked = !$user->is_blocked;
        $user->save();

        $message = $user->is_blocked ? 'Пользователь заблокирован.' : 'Пользователь разблокирован.';
        
        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }
}
