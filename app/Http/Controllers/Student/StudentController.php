<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class StudentController extends Controller
{
  


public function dashboard()
{
    // Get all courses uploaded by admin
    $courses = Course::all();

    return view('students.dashboard', compact('courses'));
}
}