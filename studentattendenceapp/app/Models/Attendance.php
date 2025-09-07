<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    // Allow mass–assignment on these columns
    protected $fillable = [
        'user_id',
        'date',
        'status',
        'remarks',   // remove if you truly never need remarks
    ];
}
