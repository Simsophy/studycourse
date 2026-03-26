<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function resume(Course $course)
    {
        $user = auth()->user();

        if (!$user->can_view_content) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Your lesson access is disabled by admin.');
        }

        $isEnrolled = $user->courses()->where('courses.id', $course->id)->exists()
            || $user->lessons()->where('lessons.course_id', $course->id)->exists();

        if (!$isEnrolled) {
            return redirect()->route('user.courses.lessons.index', $course->id)
                ->with('error', 'Please enroll first before starting this course.');
        }

        $lessonIds = $course->lessons()->orderBy('id')->pluck('id')->values();

        if ($lessonIds->isEmpty()) {
            return redirect()->route('user.courses.lessons.index', $course->id)
                ->with('error', 'No lessons available for this course yet.');
        }

        $lastViewedLessonId = $user->lessons()
            ->where('lessons.course_id', $course->id)
            ->orderByDesc('lesson_user.updated_at')
            ->value('lessons.id');

        if (!$lastViewedLessonId) {
            return redirect()->route('user.lessons.show', $lessonIds->first());
        }

        return redirect()->route('user.lessons.show', $lastViewedLessonId);
    }

    // Show all lessons for a specific course
    public function index(Course $course)
    {
        $user = auth()->user();

        // Load lessons with admin info (no students to avoid recursion)
        $lessons = Lesson::with('admin')
            ->where('course_id', $course->id)
            ->get();

        $isEnrolled = $user->courses()->where('courses.id', $course->id)->exists()
            || $user->lessons()->where('lessons.course_id', $course->id)->exists();

        $startedCourseIds = $user->lessons()
            ->pluck('lessons.course_id')
            ->unique()
            ->values()
            ->all();

        return view('students.lessons.index', compact('course', 'lessons', 'isEnrolled', 'startedCourseIds'));
    }
public function allLessons()
{
    $user = auth()->user();

    // Courses page should read from admin-created courses table
    $courses = Course::query()
        ->with(['lessons' => function ($query) {
            $query->latest();
        }])
        ->withCount('lessons')
        ->latest()
        ->get();

    $enrolledCourseIds = $user->courses()->pluck('courses.id')->all();
    $enrolledLessonIds = $user->lessons()->pluck('lessons.id')->all();
    $startedCourseIds = $user->lessons()
        ->pluck('lessons.course_id')
        ->unique()
        ->values()
        ->all();

    return view('students.lessons.index', [
        'courses' => $courses,
        'lessons' => collect(),
        'course' => null,
        'isEnrolled' => false,
        'enrolledCourseIds' => $enrolledCourseIds,
        'enrolledLessonIds' => $enrolledLessonIds,
        'startedCourseIds' => $startedCourseIds,
    ]);
}
    // Show a single lesson (only if user enrolled)
    public function show(Lesson $lesson)
    {
        $user = auth()->user();

        if (!$user->can_view_content) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Your lesson access is disabled by admin.');
        }

        // Only allow access if enrolled
        $isEnrolled = $user->courses()->where('courses.id', $lesson->course_id)->exists()
            || $user->lessons()->where('lessons.id', $lesson->id)->exists();

        if (!$isEnrolled) {
            abort(403, 'Access denied. Please enroll first.');
        }

        $user->lessons()->syncWithoutDetaching([
            $lesson->id => ['course_id' => $lesson->course_id],
        ]);
        $user->lessons()->updateExistingPivot($lesson->id, ['updated_at' => now()]);

        return view('students.lessons.show', compact('lesson'));
    }

    // Enroll user in a course
    public function enroll(Course $course)
    {
        $user = auth()->user();

        if (!$user->can_save_content) {
            return redirect()->back()->with('error', 'You are not allowed to save/enroll courses.');
        }

        // Attach without duplicating
        $user->courses()->syncWithoutDetaching([$course->id]);

        return redirect()->back()->with('success', 'You are now enrolled in course: ' . $course->name);
    }

    public function download(Lesson $lesson)
    {
        $user = auth()->user();

        if (!$user->can_download_content) {
            return redirect()->route('user.lessons.show', $lesson->id)
                ->with('error', 'Download permission is disabled by admin.');
        }

        $isEnrolled = $user->courses()->where('courses.id', $lesson->course_id)->exists()
            || $user->lessons()->where('lessons.id', $lesson->id)->exists();

        if (!$isEnrolled) {
            abort(403, 'Access denied. Please enroll first.');
        }

        if (!$lesson->video_url || !Storage::disk('public')->exists($lesson->video_url)) {
            abort(404, 'Video file not found.');
        }

        return Storage::disk('public')->download($lesson->video_url, basename($lesson->video_url));
    }
}