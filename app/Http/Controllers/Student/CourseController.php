<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;

class CourseController extends Controller
{
    // Show all lessons for a specific course
    public function index(Course $course)
    {
        // Load lessons with admin info (no students to avoid recursion)
        $lessons = $course->lessons()->with('admin')->get();

        // Get IDs of lessons user is already enrolled in
        $user = auth()->user();
        $enrolledLessonIds = $user->lessons()->pluck('id')->toArray();

        return view('students.lessons.index', compact('course', 'lessons', 'enrolledLessonIds'));
    }
public function allLessons()
{
    $user = auth()->user();

    // Correct: call with() on the relationship query
    $lessons = $user->lessons()->with('course', 'admin')->get();

    return view('students.lessons.index', compact('lessons'));
}
    // Show a single lesson (only if user enrolled)
    public function show(Lesson $lesson)
    {
        $user = auth()->user();

        // Only allow access if enrolled
        if (!$user->lessons()->where('lesson_id', $lesson->id)->exists()) {
            abort(403, 'Access denied. Please enroll first.');
        }

        return view('students.lessons.show', compact('lesson'));
    }

    // Enroll user in a lesson
    public function enroll(Lesson $lesson)
    {
        $user = auth()->user();

        // Attach without duplicating
        $user->lessons()->syncWithoutDetaching($lesson->id);

        return redirect()->back()->with('success', 'You are now enrolled in: ' . $lesson->title);
    }
}