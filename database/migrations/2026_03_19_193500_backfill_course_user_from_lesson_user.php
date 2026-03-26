<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!DB::getSchemaBuilder()->hasTable('course_user') || !DB::getSchemaBuilder()->hasTable('lesson_user')) {
            return;
        }

        $enrollments = DB::table('lesson_user')
            ->join('lessons', 'lesson_user.lesson_id', '=', 'lessons.id')
            ->select('lessons.course_id', 'lesson_user.user_id')
            ->distinct()
            ->get();

        foreach ($enrollments as $enrollment) {
            DB::table('course_user')->updateOrInsert(
                [
                    'course_id' => $enrollment->course_id,
                    'user_id' => $enrollment->user_id,
                ],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        // Intentionally left empty to avoid deleting existing real enrollments.
    }
};
