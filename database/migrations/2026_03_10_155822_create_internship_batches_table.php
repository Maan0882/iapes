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
        Schema::create('internship_batches', function (Blueprint $table) {
            $table->id();

            // Use foreignId for team_id (assuming it references intern_teams table)
            // Constrained() automatically links to 'id' on 'intern_teams'
            $table->foreignId('team_id')->nullable()->constrained('intern_teams')->nullOnDelete();

            $table->string('batch_name');

            // Use time() for batch timing
            $table->string('batch_timing')->nullable();
            
            // Use integer for number of interns
            $table->integer('no_of_interns')->default(0);
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_batches');
    }
};
