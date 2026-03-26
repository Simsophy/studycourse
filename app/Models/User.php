<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, MustVerifyEmailTrait;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'can_view_content',
        'can_save_content',
        'can_download_content',
    ];

    protected $casts = [
        'can_view_content' => 'boolean',
        'can_save_content' => 'boolean',
        'can_download_content' => 'boolean',
    ];

    // Users enroll in courses through pivot table: course_user
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id')
            ->withTimestamps();
    }

    // Optional: keep lesson relation for lesson-level progress if needed
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user', 'user_id', 'lesson_id')->withTimestamps();
    }
}