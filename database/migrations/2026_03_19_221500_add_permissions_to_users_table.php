<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('can_view_content')->default(true)->after('role');
            $table->boolean('can_save_content')->default(true)->after('can_view_content');
            $table->boolean('can_download_content')->default(false)->after('can_save_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'can_view_content',
                'can_save_content',
                'can_download_content',
            ]);
        });
    }
};
