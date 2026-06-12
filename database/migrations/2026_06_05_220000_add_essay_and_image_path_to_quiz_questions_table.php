<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz_questions', 'image_path')) {
                $table->string('image_path')->nullable()->after('question_image');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE quiz_questions MODIFY question_type ENUM('mcq', 'true_false', 'essay') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE quiz_questions MODIFY question_type ENUM('mcq', 'true_false') NOT NULL");
        }

        Schema::table('quiz_questions', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_questions', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};
