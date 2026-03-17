<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    // Admin uploads many lessons
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}