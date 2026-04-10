<?php // Opening PHP tag

namespace App\Models; // Define the namespace for application models

use Illuminate\Foundation\Auth\User as Authenticatable; // Import base authentication model
use Illuminate\Notifications\Notifiable; // Import the Notifiable trait for sending alerts/emails

class Admin extends Authenticatable // Define the Admin model extending authenticatable
{
    use Notifiable; // Use the Notifiable trait for this model

    // Mass assignable attributes (fields that can be saved via bulk creation)
    protected $fillable = [ 
        'name', // The standard profile name
        'username', // The login identification name
        'email', // The unique email address
        'password', // The hashed security password
        'role', // The access level (admin or developer)
    ];

    // Define the relationship: An Admin can upload many lessons
    public function lessons() // Relationship method
    {
        return $this->hasMany(Lesson::class); // Establish One-to-Many relationship with Lesson model
    }
} // End of model class