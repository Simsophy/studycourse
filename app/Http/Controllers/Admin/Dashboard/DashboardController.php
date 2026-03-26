<?php


namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    // Separate admin login page (guest admin)
    public function preLogin()
    {
        return view('admin.auth.login');
    }

    // Login form submit
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim((string) $request->input('login', $request->input('email')));

        $credentialAttempts = [];

        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $credentialAttempts[] = ['email' => $loginInput, 'password' => $request->password];
        } else {
            if (Schema::hasColumn('admins', 'username')) {
                $credentialAttempts[] = ['username' => $loginInput, 'password' => $request->password];
            }

            if (Schema::hasColumn('admins', 'name')) {
                $credentialAttempts[] = ['name' => $loginInput, 'password' => $request->password];
            }
        }

        foreach ($credentialAttempts as $credentials) {
            if (Auth::guard('admin')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
        }

        return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
    }

    // Second page (full admin panel)
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'courses' => Course::count(),
            'lessons' => Lesson::count(),
            'enrollments' => \DB::table('course_user')->count(),
        ];

        $recentCourses = Course::query()
            ->withCount('lessons')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::query()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.panel', compact('stats', 'recentCourses', 'recentUsers')); // full layout/admin with sidebar + main content
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}