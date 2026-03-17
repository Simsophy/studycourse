<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateCourseTablesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:course-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create course_materials and course_enrollments tables manually';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Create course_materials table
            DB::statement('
                CREATE TABLE IF NOT EXISTS course_materials (
                    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    course_id BIGINT UNSIGNED NOT NULL,
                    title VARCHAR(255) NOT NULL,
                    type VARCHAR(255) NOT NULL,
                    file_path VARCHAR(255) NOT NULL,
                    description LONGTEXT NULL,
                    `order` INT NOT NULL DEFAULT 0,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ');
            $this->info('✓ course_materials table created');

            // Create course_enrollments table
            DB::statement('
                CREATE TABLE IF NOT EXISTS course_enrollments (
                    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    course_id BIGINT UNSIGNED NOT NULL,
                    user_id BIGINT UNSIGNED NOT NULL,
                    enrolled_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_enrollment (course_id, user_id),
                    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ');
            $this->info('✓ course_enrollments table created');
            
            // Record migration
            DB::table('migrations')->updateOrInsert(
                ['migration' => '2026_03_01_100000_create_course_materials_table'],
                ['batch' => DB::table('migrations')->max('batch') + 1]
            );
            $this->info('✓ Migration recorded in database');
            
            $this->info("\n✅ All tables created successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
