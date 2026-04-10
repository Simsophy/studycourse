<?php // Opening PHP tag

namespace App\Models; // Define the namespace for models

use Illuminate\Foundation\Auth\User as Authenticatable; // Import base classes for authentication
use Illuminate\Contracts\Auth\MustVerifyEmail; // Import interface for email verification
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait; // Import the email verification logic trait
use Illuminate\Notifications\Notifiable; // Import notifications support

class User extends Authenticatable implements MustVerifyEmail // Define User model (students)
{
    use Notifiable, MustVerifyEmailTrait; // Apply traits for notifications and email verification

    // List of attributes that are mass-assignable (can be set in bulk)
    protected $fillable = [ 
        'name', // Full name of the student
        'username', // Unique username for login
        'email', // Unique email address
        'password', // Securely hashed password
        'google_id', // ID linked from Google OAuth login
        'avatar', // Path to profile picture
        'can_view_content', // Permission flag to access lessons
        'can_save_content', // Permission flag to enroll in courses
        'can_download_content', // Permission flag to download video files
    ];

    // Specify internal data types for specific columns
    protected $casts = [ 
        'can_view_content' => 'boolean', // Ensure value is treated as true/false
        'can_save_content' => 'boolean', // Ensure value is treated as true/false
        'can_download_content' => 'boolean', // Ensure value is treated as true/false
    ];

    // Define the relationship: A student can enroll in many courses via a pivot table
    public function courses() // Many-to-Many relationship method
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id') // Link via 'course_user' table
            ->withTimestamps(); // Automatically manage created_at/updated_at on the pivot
    }

    // Define the relationship: Track which specific lessons a student has viewed
    public function lessons() // Many-to-Many relationship for progress tracking
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user', 'user_id', 'lesson_id')->withTimestamps(); // Link via 'lesson_user'
    }
} // End of student model class