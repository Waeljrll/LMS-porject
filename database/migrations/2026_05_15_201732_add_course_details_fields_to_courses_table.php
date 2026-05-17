<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->text('requirements')->nullable()->after('status');
            $table->text('who_is_it_for')->nullable()->after('requirements');
            $table->integer('duration_hours')->default(0)->after('who_is_it_for');
            $table->integer('duration_minutes')->default(0)->after('duration_hours');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['requirements', 'who_is_it_for', 'duration_hours', 'duration_minutes']);
        });
    }
};
