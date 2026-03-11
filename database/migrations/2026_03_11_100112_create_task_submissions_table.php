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

            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('intern_id');

            $table->text('submission_text')->nullable();
            $table->string('submission_file')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->enum('status',['submitted','reviewed','approved','rejected'])
                  ->default('submitted');

            $table->timestamps();

            $table->foreign('task_id')->references('task_id')->on('tasks')->cascadeOnDelete();
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
