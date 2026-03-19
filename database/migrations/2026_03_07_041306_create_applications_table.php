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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_code')->unique()->nullable(); // 👈 NEW FIELD
            $table->string('email')->index();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('college')->nullable();
            $table->string('degree')->nullable();
            $table->string('year')->nullable();
            $table->decimal('cgpa', 4, 2)->nullable();
            $table->string('domain')->nullable();
            $table->integer('duration')->nullable();
            $table->enum('duration_unit', ['months','days','hours'])
                  ->default('months');
            $table->text('skills')->nullable();
            $table->string('resume_path')->nullable();
            $table->enum('status', [
                'applied',
                'interview_scheduled',
                'interviewed',
                'shortlisted',
                'rejected'
            ])->default('applied');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
