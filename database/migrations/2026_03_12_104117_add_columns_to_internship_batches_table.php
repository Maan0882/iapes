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
        //
        Schema::table('internship_batches', function (Blueprint $table) {
        // Use time() for batch timing
        $table->time('batch_timing')->nullable();
        
        // Use integer for number of interns
        $table->integer('no_of_interns')->default(0);
        
        // Use foreignId for team_id (assuming it references intern_teams table)
        // Constrained() automatically links to 'id' on 'intern_teams'
        $table->foreignId('team_id')->nullable()->constrained('intern_teams')->nullOnDelete();
        $table->foreignId('intern_id')->nullable()->constrained('interns')->nullOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('internship_batches', function (Blueprint $table) {
        $table->dropForeign(['team_id','intern_id']);
        $table->dropColumn(['batch_timing', 'no_of_interns', 'team_id','intern_id']);
    });
    }
};
