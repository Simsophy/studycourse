<?php // Opening PHP tag

namespace App\Models; // Define the namespace for application models

use Illuminate\Database\Eloquent\Model; // Import the base Eloquent model class

class Course extends Model // Define the Course model class
{
    // Attributes that can be filled via mass assignment (e.g., Course::create([]))
    protected $fillable = [
        'name', // The display name of the course
        'description', // The textual content describing the course
        'status', // The lifecycle status (active, inactive, etc.)
    ];

    // Define the relationship: A course contains many lessons
    public function lessons() // Relationship method
    {
        return $this->hasMany(Lesson::class); // Establish One-to-Many relationship with Lesson model
    }

    // Define the relationship: Which students are enrolled in this course
    public function students() // Many-to-Many relationship method
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id') // Link via pivot table
                    ->withTimestamps(); // Track when enrollment occurred
    }
} // End of course model class