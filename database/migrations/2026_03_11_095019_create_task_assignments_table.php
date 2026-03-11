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

            $table->unsignedBigInteger('task_id');

            $table->enum('assigned_type', ['intern','team','batch']);

            $table->unsignedBigInteger('intern_id')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();

            $table->timestamps();

            $table->foreign('task_id')->references('task_id')->on('tasks')->cascadeOnDelete();
        
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
