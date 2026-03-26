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
        Schema::create('offer_letters', function (Blueprint $table) {
            $table->id();
            $table->string('offer_letter_code')->unique();
            $table->foreignId('application_id')->nullable()->constrained('applications')->nullOnDelete();
            $table->foreignId('intern_id')->nullable()->constrained('interns')->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('college')->nullable();
            $table->string('university')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('joining_date');
            $table->date('completion_date');
            $table->string('internship_role');
            $table->string('internship_position')->nullable();
            $table->string('working_hours');
            $table->string('template')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('is_accepted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_letters');
    }
};
