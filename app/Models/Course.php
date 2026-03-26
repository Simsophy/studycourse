<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'video_url',
        'image'
    ];

    // Each course has many lessons
   public function lessons() {
    return $this->hasMany(Lesson::class);
}

public function students() {
    return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
                ->withTimestamps();
}
}