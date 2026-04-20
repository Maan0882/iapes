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
        Schema::create('manual_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('intern_name');
            $table->string('intern_code')->unique();
            $table->string('internship_role');
            $table->date('joining_date');
            $table->date('completion_date');
            $table->date('issuing_date');
            $table->string('cert_token')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_certificates');
    }
};
