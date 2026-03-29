<?php

namespace App\Http\Controllers\Admin\Courses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    // Show all courses
    public function index()
    {
        $this->authorize('viewAny', Course::class);

        $courses = Course::with(['lessons.admin'])
            ->latest('id')
            ->paginate(9);

        return view('admin.courses.index', compact('courses'));
    }

    // Show create form
    public function create()
    {
        $this->authorize('create', Course::class);

        return view('admin.courses.create');
    }

    // Store new course + first lesson
    public function store(Request $request)
    {
        $this->authorize('create', Course::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'lesson_title' => 'nullable|string|max:255|required_with:lesson_description,lesson_video',
            'lesson_description' => 'nullable|string|required_with:lesson_title,lesson_video',
            'lesson_video' => 'nullable|mimes:mp4,avi,mov,mpeg|max:102400', // max 100MB
        ]);

        // Upload course image
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('courses/images', 'public') : null;

        // Create course
        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        $hasLessonInput = $request->filled('lesson_title')
            || $request->filled('lesson_description')
            || $request->hasFile('lesson_video');

        // Create first lesson only when lesson data is provided
        if ($hasLessonInput) {
            $lessonVideoPath = $request->hasFile('lesson_video')
                ? $request->file('lesson_video')->store('lessons/videos', 'public')
                : null;

            $course->lessons()->create([
                'admin_id' => auth('admin')->id(),
                'title' => $request->lesson_title,
                'description' => $request->lesson_description,
                'video_url' => $lessonVideoPath,
            ]);
        }

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course and initial lesson created successfully');
    }

    // Show edit form
    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        $course->load(['lessons.admin']);

        return view('admin.courses.edit', compact('course'));
    }

    // Update course
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'lesson_title' => 'nullable|string|max:255|required_with:lesson_description,lesson_video',
            'lesson_description' => 'nullable|string|required_with:lesson_title,lesson_video',
            'lesson_video' => 'nullable|mimes:mp4,avi,mov,mpeg|max:102400',
        ]);

        // Keep current paths
        $imagePath = $course->image;

        // Upload new image if exists
        if ($request->hasFile('image')) {
            if ($course->image && file_exists(storage_path('app/public/' . $course->image))) {
                unlink(storage_path('app/public/' . $course->image));
            }
            $imagePath = $request->file('image')->store('courses/images', 'public');
        }

        // Update course
        $course->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        $firstLesson = $course->lessons()->oldest('id')->first();
        $hasLessonInput = $request->filled('lesson_title')
            || $request->filled('lesson_description')
            || $request->hasFile('lesson_video');

        if ($hasLessonInput) {
            $lessonVideoPath = $firstLesson?->video_url;

            if ($request->hasFile('lesson_video')) {
                if ($firstLesson?->video_url && file_exists(storage_path('app/public/' . $firstLesson->video_url))) {
                    unlink(storage_path('app/public/' . $firstLesson->video_url));
                }

                $lessonVideoPath = $request->file('lesson_video')->store('lessons/videos', 'public');
            }

            $lessonPayload = [
                'admin_id' => auth('admin')->id() ?? $firstLesson?->admin_id,
                'title' => $request->lesson_title,
                'description' => $request->lesson_description,
                'video_url' => $lessonVideoPath,
            ];

            if ($firstLesson) {
                $firstLesson->update($lessonPayload);
            } else {
                $course->lessons()->create($lessonPayload);
            }
        }

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course updated successfully!');
    }

    // Delete course
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        // Delete course image
        if ($course->image && file_exists(storage_path('app/public/' . $course->image))) {
            unlink(storage_path('app/public/' . $course->image));
        }

        // Delete related lessons
        foreach ($course->lessons as $lesson) {
            if ($lesson->video_url && file_exists(storage_path('app/public/' . $lesson->video_url))) {
                unlink(storage_path('app/public/' . $lesson->video_url));
            }
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course deleted successfully!');
    }
}