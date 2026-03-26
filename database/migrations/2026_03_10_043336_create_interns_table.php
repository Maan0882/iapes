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
        Schema::create('interns', function (Blueprint $table) {
            $table->id();
            // Authentication & Identity
            $table->string('intern_code')->unique()->nullable();
            $table->foreignId('application_id')->nullable()
                  ->constrained()
                  ->cascadeOnDelete(); 
            $table->foreignId('offer_letter_id')
                ->nullable()
                ->constrained('offer_letters')
                ->nullOnDelete();
            $table->string('username')->unique();    // Same as code for login
            $table->string('password');
            // Profile Data
            $table->string('name');
            $table->string('email')->unique();
            $table->date('joining_date');
            // Status
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interns');
    }
};
