<?php // Opening PHP tag

namespace App\Models; // Define the namespace for application models

use Illuminate\Database\Eloquent\Model; // Import the base Eloquent model class

class Lesson extends Model // Define the Lesson model class
{
    // Attributes that can be filled via mass assignment
    protected $fillable = [ 
        'course_id', // Foreign key linking to the parent Course
        'title', // The display name of the specific lesson
        'description', // Brief summary or transcript of the lesson content
        'video_url', // Path or Link to the hosted video file
        'admin_id' // Foreign key linking to the Admin who uploaded this lesson
    ];

    // Define the relationship: Each lesson belongs to exactly one parent Course
    public function course() // Relationship method
    {
        return $this->belongsTo(Course::class); // Establish Inverse One-to-Many relationship
    }

    // Define the relationship: Track which Admin created/uploaded this specific lesson
    public function admin() // Relationship method
    {
        return $this->belongsTo(Admin::class); // Establish relationship with Admin model
    }

    // Define the relationship: Track which students have viewed or unlocked this lesson
    public function users() // Many-to-Many relationship for lesson-level progress
    {
        return $this->belongsToMany(User::class, 'lesson_user', 'lesson_id', 'user_id') // Link via pivot table
            ->withTimestamps(); // Track exact viewing times for "Resume" features
    }
} // End of lesson model class