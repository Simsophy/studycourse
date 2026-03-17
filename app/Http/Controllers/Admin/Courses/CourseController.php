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
        $courses = Course::all();
        return view('admin.courses.index', compact('courses'));
    }

    // Show create form
    public function create()
    {
        return view('admin.courses.create');
    }

    // Store new course + first lesson
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'lesson_title' => 'required|string|max:255',
            'lesson_description' => 'nullable|string',
            'lesson_video' => 'required|mimes:mp4,avi,mov,mpeg|max:102400', // max 100MB
        ]);

        // Upload course image
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('courses/images', 'public') : null;

        // Create course
        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'video_url' => null, // course itself does not store video
        ]);

        // Upload lesson video
        $lessonVideoPath = $request->hasFile('lesson_video') ? $request->file('lesson_video')->store('lessons/videos', 'public') : null;

        // Create first lesson for course
        $course->lessons()->create([
            'admin_id' => auth('admin')->id(),
            'title' => $request->lesson_title,
            'description' => $request->lesson_description,
            'video_url' => $lessonVideoPath,
            'image' => null, // optional lesson image
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course and initial lesson created successfully');
    }

    // Show edit form
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    // Update course
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
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
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course updated successfully!');
    }

    // Delete course
    public function destroy(Course $course)
    {
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