<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create and return a new registered user, assigning role by email pattern.
     */
    public function create(array $input): User
    {
        // ✅ Validate incoming registration data
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ])->validate();

        // ✅ Create the user in DB
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // ✅ Normalize email to lowercase
        $email = strtolower($user->email);

        // ✅ Define role pattern groups
        $teacherPatterns = ['_teach@', '_teach@yahoo.', '_teach@outlook.'];
        $studentPatterns = ['_std@', '_std@yahoo.', '_std@outlook.'];

        // ✅ Pattern match helper function
        $matchesPattern = function ($email, $patterns) {
            foreach ($patterns as $pattern) {
                if (str_contains($email, $pattern)) {
                    return true;
                }
            }
            return false;
        };

        // ✅ Role assignment logic
        if (str_contains($email, '_admin1001')) {
            // 🔒 Prevent duplicate admin
            if (!User::role('admin')->exists()) {
                $user->syncRoles('admin');
            } else {
                Log::warning('Duplicate admin attempt: ' . $email);
                $user->syncRoles('teacher'); // optional fallback
            }
        } elseif ($matchesPattern($email, $teacherPatterns)) {
            $user->syncRoles('teacher');
        } elseif ($matchesPattern($email, $studentPatterns)) {
            $user->syncRoles('student');
        } else {
            Log::info('User registered with unknown format: ' . $email);
            // Optional fallback: $user->syncRoles('student');
        }

        return $user;
    }
}
