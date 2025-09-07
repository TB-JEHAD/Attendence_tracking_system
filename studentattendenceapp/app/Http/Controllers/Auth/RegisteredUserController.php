<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Show registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle new user registration.
     */
    public function store(Request $request): RedirectResponse
    {
        // 🔒 Validate inputs
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 👤 Create new user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 📣 Fire event
        event(new Registered($user));

        // 🧠 Assign role based on email pattern
        if (Str::contains(Str::lower($user->email), '_std')) {
            $user->assignRole('student');
               \Log::info('✅ Assigned role: student to ' . $user->email);
        } elseif (Str::contains($user->email, '_teach')) {
            $user->assignRole('teacher');
            \Log::info('✅ Assigned role: teacher to ' . $user->email);
        } else {
            // ✅ Admin logic: allow only one admin
            $adminCount = User::role('admin')->count();

            if ($adminCount === 0) {
                $user->assignRole('admin');
                  \Log::info('✅ Assigned role: admin to ' . $user->email);
            } else {
                // ❎ Admin already exists — fallback to student
                $user->assignRole('student');
                \Log::info('✅ Assigned fallback role: student to ' . $user->email);
            }
        }

        // 🔐 Log in
        Auth::login($user);

        // 🚀 Redirect based on assigned role
        if ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        } elseif ($user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard');
        } else {
            return redirect()->route('admin.dashboard');
        }
    }
}
