<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DefaultAdminCourseLessonSeeder extends Seeder
{
    /**
     * Seed default admin, course, and lesson data.
     */
    public function run(): void
    {
        $now = now();

        if (Schema::hasTable('users')) {
            $defaultUserEmail = 'user@example.com';

            $userExists = DB::table('users')->where('email', $defaultUserEmail)->exists();

            if (!$userExists) {
                $userData = [
                    'email' => $defaultUserEmail,
                    'password' => Hash::make('12345678'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (Schema::hasColumn('users', 'name')) {
                    $userData['name'] = 'Default User';
                }

                if (Schema::hasColumn('users', 'username')) {
                    $userData['username'] = 'user';
                }

                if (Schema::hasColumn('users', 'email_verified_at')) {
                    $userData['email_verified_at'] = $now;
                }

                DB::table('users')->insert($userData);
            }
        }

        $adminId = null;
        if (Schema::hasTable('admins')) {
            $adminEmail = 'admin@example.com';

            $admin = DB::table('admins')->where('email', $adminEmail)->first();

            if (!$admin) {
                $adminData = [
                    'email' => $adminEmail,
                    'password' => Hash::make('12345678'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (Schema::hasColumn('admins', 'name')) {
                    $adminData['name'] = 'Default Admin';
                }

                if (Schema::hasColumn('admins', 'username')) {
                    $adminData['username'] = 'admin';
                }

                if (Schema::hasColumn('admins', 'role')) {
                    $adminData['role'] = 'admin';
                }

                $adminId = DB::table('admins')->insertGetId($adminData);
            } else {
                $adminId = $admin->id;
            }
        }

        if (!Schema::hasTable('courses')) {
            return;
        }

        $defaultCourses = [
            [
                'name' => 'Default Course',
                'description' => 'This is a default course created by seeder.',
                'status' => 'active',
                'image' => null,
                'video_url' => null,
                'lessons' => [
                    [
                        'title' => 'Default Lesson',
                        'description' => 'This is a default lesson for the default course.',
                        'video_url' => null,
                    ],
                ],
            ],
            [
                'name' => 'Laravel Basics',
                'description' => 'Introduction to routing, controllers, Blade views, and migrations.',
                'status' => 'active',
                'image' => null,
                'video_url' => null,
                'lessons' => [
                    [
                        'title' => 'Routing & Controllers',
                        'description' => 'Learn how Laravel routes connect to controllers.',
                        'video_url' => null,
                    ],
                ],
            ],
            [
                'name' => 'PHP OOP Fundamentals',
                'description' => 'Understand classes, objects, inheritance, and model relationships.',
                'status' => 'active',
                'image' => null,
                'video_url' => null,
                'lessons' => [
                    [
                        'title' => 'Classes and Objects',
                        'description' => 'Core OOP structure in PHP for beginner developers.',
                        'video_url' => null,
                    ],
                ],
            ],
        ];

        foreach ($defaultCourses as $courseSeed) {
            $course = DB::table('courses')->where('name', $courseSeed['name'])->first();

            if (!$course) {
                $courseData = [
                    'name' => $courseSeed['name'],
                    'description' => $courseSeed['description'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (Schema::hasColumn('courses', 'image')) {
                    $courseData['image'] = $courseSeed['image'];
                }

                if (Schema::hasColumn('courses', 'status')) {
                    $courseData['status'] = $courseSeed['status'];
                }

                if (Schema::hasColumn('courses', 'video_url')) {
                    $courseData['video_url'] = $courseSeed['video_url'];
                }

                $courseId = DB::table('courses')->insertGetId($courseData);
            } else {
                $courseId = $course->id;
            }

            if (!$courseId || !Schema::hasTable('lessons')) {
                continue;
            }

            foreach ($courseSeed['lessons'] as $lessonSeed) {
                $lessonExists = DB::table('lessons')
                    ->where('course_id', $courseId)
                    ->where('title', $lessonSeed['title'])
                    ->exists();

                if ($lessonExists) {
                    continue;
                }

                $lessonData = [
                    'course_id' => $courseId,
                    'title' => $lessonSeed['title'],
                    'description' => $lessonSeed['description'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (Schema::hasColumn('lessons', 'video_url')) {
                    $lessonData['video_url'] = $lessonSeed['video_url'];
                }

                if (Schema::hasColumn('lessons', 'admin_id') && $adminId) {
                    $lessonData['admin_id'] = $adminId;
                }

                DB::table('lessons')->insert($lessonData);
            }
        }
    }
}
