<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
  


public function dashboard()
{
    $user = auth()->user();

    // Show all courses created by admin (including courses without lessons yet)
    $courses = Course::query()
        ->with(['lessons.admin'])
        ->withCount('students')
        ->withCount('lessons')
        ->latest()
        ->get();

    $enrolledCourseIds = $user->courses()->pluck('courses.id');
    $startedCourseIds = $user->lessons()
        ->pluck('lessons.course_id')
        ->unique()
        ->values();

    $stats = [
        'total_courses' => $courses->count(),
        'enrolled_courses' => $enrolledCourseIds->count(),
        'total_lessons' => Lesson::count(),
        'my_permissions' => collect([
            $user->can_view_content,
            $user->can_save_content,
            $user->can_download_content,
        ])->filter()->count(),
    ];

    $recommendedCourses = $courses
        ->whereNotIn('id', $enrolledCourseIds)
        ->take(4)
        ->values();

    return view('students.dashboard', [
        'courses' => $courses,
        'stats' => $stats,
        'recommendedCourses' => $recommendedCourses,
        'enrolledCourseIds' => $enrolledCourseIds,
        'startedCourseIds' => $startedCourseIds,
    ]);
}

public function settings()
{
    return view('students.settings', [
        'user' => auth()->user(),
    ]);
}

public function updateSettings(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
        'current_password' => ['nullable', 'string', 'required_with:password'],
        'password' => ['nullable', 'string', 'min:6', 'confirmed'],
    ]);

    if (!empty($validated['password'])) {
        if (!Hash::check($validated['current_password'] ?? '', $user->password)) {
            return back()->withErrors([
                'current_password' => 'Your current password is incorrect.',
            ])->withInput();
        }

        $user->password = Hash::make($validated['password']);
    }

    $user->name = $validated['name'];
    $user->username = $validated['username'];
    $user->email = $validated['email'];
    $user->save();

    return back()->with('status', 'Your account settings have been updated.');
}
}