<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_url',
        'admin_id'
    ];

    // Lesson belongs to a course
  public function course()
{
    return $this->belongsTo(Course::class);
}

public function admin()
{
    return $this->belongsTo(Admin::class);
}

public function users()
{
    return $this->belongsToMany(User::class, 'lesson_user', 'lesson_id', 'user_id')
        ->withTimestamps();
}
}