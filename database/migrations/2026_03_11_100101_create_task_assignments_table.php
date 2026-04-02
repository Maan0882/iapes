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
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id('task_assignment_id');
            $table->foreignId('task_id')->constrained('tasks', 'task_id')->cascadeOnDelete();
            $table->enum('assigned_type', ['intern','team','batch']);

            // ✅ Properly constrained foreign keys
            $table->foreignId('intern_id')->nullable()->constrained('interns')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('intern_teams')->nullOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('internship_batches')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
};
