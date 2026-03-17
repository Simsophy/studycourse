<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    // Users enroll in courses
   public function courses() {
    return $this->belongsToMany(Course::class, 'lesson_user', 'user_id', 'course_id')
                ->withTimestamps();
}

public function lessons()
{
    return $this->belongsToMany(Lesson::class, 'lesson_user', 'user_id', 'lesson_id')->withTimestamps();
}
}