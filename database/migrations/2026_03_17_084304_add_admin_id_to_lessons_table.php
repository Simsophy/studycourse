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
        if (!Schema::hasTable('lessons') || !Schema::hasTable('admins')) {
            return;
        }

        if (Schema::hasColumn('lessons', 'admin_id')) {
            return;
        }

        Schema::table('lessons', function (Blueprint $table) {
            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('admins')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(["admin_id"]);
            $table->dropColumn("admin_id");
        });
    }
};
