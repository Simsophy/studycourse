<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CourseMaterialController extends Controller
{
    /**
     * Show materials for a specific course.
     */
    public function index(Course $course)
    {
        $this->authorize('view', $course);

        $materials = $course->materials()
            ->ordered()
            ->get();

        return view('admin.courses.materials.index', compact('course', 'materials'));
    }

    /**
     * Show the form to create a new material.
     */
    public function create(Course $course)
    {
        $this->authorize('update', $course);

        return view('admin.courses.materials.create', compact('course'));
    }

    /**
     * Store a new material for the course.
     */
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(['document', 'image', 'video', 'other'])],
            'file' => 'required|file|max:10240', // 10MB max
            'order' => 'nullable|integer|min:0',
        ]);

        // Store the uploaded file in the appropriate directory
        $directory = 'course-materials/' . $course->id;
        $file = $request->file('file');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($directory, $fileName, 'public');

        $course->materials()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'file_path' => $filePath,
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.courses.materials.index', $course)
            ->with('success', 'Material uploaded successfully!');
    }

    /**
     * Show the form to edit an existing material.
     */
    public function edit(Course $course, CourseMaterial $material)
    {
        $this->authorize('update', $course);

        // Ensure material belongs to the course
        if ($material->course_id !== $course->id) {
            abort(404, 'Material not found in this course');
        }

        return view('admin.courses.materials.edit', compact('course', 'material'));
    }

    /**
     * Update the specified material.
     */
    public function update(Request $request, Course $course, CourseMaterial $material)
    {
        $this->authorize('update', $course);

        // Ensure material belongs to the course
        if ($material->course_id !== $course->id) {
            abort(404, 'Material not found in this course');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(['document', 'image', 'video', 'other'])],
            'file' => 'nullable|file|max:10240',
            'order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'order' => $validated['order'] ?? $material->order,
        ];

        // Handle file replacement
        if ($request->hasFile('file')) {
            // Delete old file
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $directory = 'course-materials/' . $course->id;
            $file = $request->file('file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($directory, $fileName, 'public');

            $data['file_path'] = $filePath;
        }

        $material->update($data);

        return redirect()
            ->route('admin.courses.materials.index', $course)
            ->with('success', 'Material updated successfully!');
    }

    /**
     * Remove the specified material.
     */
    public function destroy(Course $course, CourseMaterial $material)
    {
        $this->authorize('update', $course);

        // Ensure material belongs to the course
        if ($material->course_id !== $course->id) {
            abort(404, 'Material not found in this course');
        }

        // Delete the file
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()
            ->route('admin.courses.materials.index', $course)
            ->with('success', 'Material deleted successfully!');
    }

    /**
     * Download the material file.
     */
    public function download(Course $course, CourseMaterial $material)
    {
        $this->authorize('view', $course);

        // Ensure material belongs to the course
        if ($material->course_id !== $course->id) {
            abort(404, 'Material not found in this course');
        }

        $filePath = storage_path('app/public/' . $material->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $material->title . '.' . $this->getFileExtension($material->file_path));
    }

    /**
     * Helper to get file extension.
     */
    private function getFileExtension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }
}
