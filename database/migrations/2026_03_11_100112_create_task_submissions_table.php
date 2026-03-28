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
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id('submission_id');

            $table->foreignId('task_id')->constrained('tasks', 'task_id')->cascadeOnDelete();
            $table->foreignId('intern_id')->constrained('interns')->cascadeOnDelete(); // ✅ Added

            $table->text('submission_text')->nullable();
            $table->string('submission_file')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->enum('status',['submitted','reviewed','approved','rejected'])
                  ->default('submitted');

            // Added fields from your data dictionary for grading [cite: 85]
            $table->text('admin_feedback')->nullable();
            $table->integer('marks')->nullable();
            $table->string('grade', 5)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};
