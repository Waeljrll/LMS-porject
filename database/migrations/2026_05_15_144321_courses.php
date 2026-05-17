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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 200);
            $table->string('slug')->unique();
            $table->string('short_description', 200);
            $table->longText('description');
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);
            $table->string('language')->default('English');
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
