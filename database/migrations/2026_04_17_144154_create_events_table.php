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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_title');
            $table->text('event_description')->nullable();
            $table->enum('event_type', ['seminar', 'hackathon', 'workshop']);
            $table->date('event_date');
            $table->enum('type', ['online', 'offline']);
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable();
            $table->string('event_certificate_template')->nullable();
            $table->enum('event_status', ['upcoming', 'completed'])->default('upcoming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
