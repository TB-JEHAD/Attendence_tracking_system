<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
protected $fillable = [
    'name',
    'email',
    'password',
];

    public function detectRoleFromEmail(): string
    {
        $email = $this->email;

        if (str_contains($email, '_admin1001@')) {
            return 'admin';
        } elseif (
            str_contains($email, '_teach@') || 
            str_contains($email, '_teach@yahoo.') || 
            str_contains($email, '_teach@outlook.')
        ) {
            return 'teacher';
        } elseif (
            str_contains($email, '_std@') || 
            str_contains($email, '_std@yahoo.') || 
            str_contains($email, '_std@outlook.')
        ) {
            return 'student';
        }

        return 'unknown';
    }
}
