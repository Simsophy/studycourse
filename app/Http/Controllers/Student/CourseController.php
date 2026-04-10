<?php // Opening PHP tag

namespace App\Http\Controllers\Student; // Define the namespace for Student-specific controllers

use App\Http\Controllers\Controller; // Import the base Controller class
use Illuminate\Http\Request; // Import the Request object for handling HTTP inputs
use App\Models\Course; // Import the Course model
use App\Models\Lesson; // Import the Lesson model
use Illuminate\Support\Facades\Storage; // Import Storage facade for file handling

class CourseController extends Controller // Define the CourseController class extending the base Controller
{
    // Resume progress functionality: Redirects user to their last viewed lesson
    public function resume(Course $course) // Method to handle resuming a course
    {
        $user = auth()->user(); // Get the currently authenticated student user

        if (!$user->can_view_content) { // Check if the student has permission to view content
            return redirect()->route('user.dashboard') // Redirect to dashboard if permission is denied
                ->with('error', 'Your lesson access is disabled by admin.'); // pass error message to the next page
        }

        // Check if user is enrolled in the course or at least one of its lessons
        $isEnrolled = $user->courses()->where('courses.id', $course->id)->exists() 
            || $user->lessons()->where('lessons.course_id', $course->id)->exists(); 

        if (!$isEnrolled) { // If user is not enrolled in the course...
            return redirect()->route('user.courses.lessons.index', $course->id) // Redirect back to course page
                ->with('error', 'Please enroll first before starting this course.'); // with enrollment required error
        }

        $lessonIds = $course->lessons()->orderBy('id')->pluck('id')->values(); // Get all lesson IDs for this course ordered by ID

        if ($lessonIds->isEmpty()) { // If the course has no lessons yet...
            return redirect()->route('user.courses.lessons.index', $course->id) // Redirect back to course page
                ->with('error', 'No lessons available for this course yet.'); // with "no lessons" error
        }

        // Find the ID of the last lesson viewed by this user in this course
        $lastViewedLessonId = $user->lessons() 
            ->where('lessons.course_id', $course->id) // Filter by current course
            ->orderByDesc('lesson_user.updated_at') // Sort by the most recently updated pivot record
            ->value('lessons.id'); // Retrieve only the lesson ID

        if (!$lastViewedLessonId) { // If user hasn't started any lesson yet...
            return redirect()->route('user.lessons.show', $lessonIds->first()); // Redirect to the first lesson of the course
        }

        return redirect()->route('user.lessons.show', $lastViewedLessonId); // Otherwise, redirect to their last viewed lesson position
    }

    // Show all lessons for a specific course (index page)
    public function index(Course $course) // Method to list lessons for a course
    {
        $user = auth()->user(); // Get current user

        // Load lessons from database specifically for this course ID
        $lessons = Lesson::with('admin') // Eager load the 'admin' who uploaded the lesson
            ->where('course_id', $course->id) // Filter by course
            ->get(); // Execute query

        // Check enrollment status for the index view logic
        $isEnrolled = $user->courses()->where('courses.id', $course->id)->exists() 
            || $user->lessons()->where('lessons.course_id', $course->id)->exists(); 

        // Get IDs of all courses the user has already started (interacted with at least one lesson)
        $startedCourseIds = $user->lessons() 
            ->pluck('lessons.course_id') // Get course IDs from lessons
            ->unique() // Ensure no duplicates
            ->values() // Re-index array
            ->all(); // Convert to standard array

        return view('students.lessons.index', compact('course', 'lessons', 'isEnrolled', 'startedCourseIds')); // Return the index view with data
    }

    // Displays all available courses in the system
    public function allLessons() // Method to show course catalog
    {
        $user = auth()->user(); // Get current user

        // Retrieve all courses with their latest lessons and lesson counts
        $courses = Course::query() 
            ->with(['lessons' => function ($query) { // Eager load lessons
                $query->latest(); // Order lessons by newest first
            }])
            ->withCount('lessons') // Count how many lessons are in each course
            ->latest() // Order courses by newest first
            ->get(); // Execute query

        $enrolledCourseIds = $user->courses()->pluck('courses.id')->all(); // Get IDs of courses user is enrolled in
        $enrolledLessonIds = $user->lessons()->pluck('lessons.id')->all(); // Get IDs of lessons user has accessed
        $startedCourseIds = $user->lessons() // Get courses the user has actually started
            ->pluck('lessons.course_id') 
            ->unique() 
            ->values() 
            ->all(); 

        return view('students.lessons.index', [ // Return the same index view with bulk course data
            'courses' => $courses,
            'lessons' => collect(), // Empty lessons collection for this variant of the view
            'course' => null, // No specific course context
            'isEnrolled' => false,
            'enrolledCourseIds' => $enrolledCourseIds,
            'enrolledLessonIds' => $enrolledLessonIds,
            'startedCourseIds' => $startedCourseIds,
        ]);
    }

    // Show a single lesson details and video player (only if user enrolled)
    public function show(Lesson $lesson) // Method to display a specific lesson
    {
        $user = auth()->user(); // Get current user

        if (!$user->can_view_content) { // Verify global content viewing permission
            return redirect()->route('user.dashboard')
                ->with('error', 'Your lesson access is disabled by admin.');
        }

        // Authorization: Only allow access if enrolled in the course or the specific lesson
        $isEnrolled = $user->courses()->where('courses.id', $lesson->course_id)->exists() 
            || $user->lessons()->where('lessons.id', $lesson->id)->exists(); 

        if (!$isEnrolled) { // If not authorized...
            abort(403, 'Access denied. Please enroll first.'); // Return 403 Forbidden exit
        }

        // Track progress: Record that the user is viewing this lesson
        $user->lessons()->syncWithoutDetaching([$lesson->id]); // Add to lesson_user without removing others
        $user->lessons()->updateExistingPivot($lesson->id, ['updated_at' => now()]); // Update 'last viewed' timestamp

        return view('students.lessons.show', compact('lesson')); // Return the lesson player view
    }

    // Enroll user in a course
    public function enroll(Course $course) // Method to handle course enrollment
    {
        $user = auth()->user(); // Get current user

        if (!$user->can_save_content) { // Check if user is allowed to enroll/save content
            return redirect()->back()->with('error', 'You are not allowed to save/enroll courses.');
        }

        // Attach the course to the user in the many-to-many relationship
        $user->courses()->syncWithoutDetaching([$course->id]); // sync without detaching prevents duplicates

        return redirect()->back()->with('success', 'You are now enrolled in course: ' . $course->name); // Redirect back with success message
    }

    // Download the video file associated with a lesson
    public function download(Lesson $lesson) // Method to serve video download
    {
        $user = auth()->user(); // Get current user

        if (!$user->can_download_content) { // Verify download permission
            return redirect()->route('user.lessons.show', $lesson->id)
                ->with('error', 'Download permission is disabled by admin.');
        }

        // Verify authorization for the specific content
        $isEnrolled = $user->courses()->where('courses.id', $lesson->course_id)->exists() 
            || $user->lessons()->where('lessons.id', $lesson->id)->exists(); 

        if (!$isEnrolled) { // Fail if not enrolled
            abort(403, 'Access denied. Please enroll first.');
        }

        // Check if the video URL exists and the file is present on the public storage
        if (!$lesson->video_url || !Storage::disk('public')->exists($lesson->video_url)) { 
            abort(404, 'Video file not found.'); // Return 404 if file missing
        }

        // Return a download response for the video file
        return Storage::disk('public')->download($lesson->video_url, basename($lesson->video_url)); 
    }
} // End of class