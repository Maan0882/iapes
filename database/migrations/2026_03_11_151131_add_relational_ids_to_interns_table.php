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
        Schema::table('interns', function (Blueprint $table) {
            // Link to the Team
            $table->foreignId('intern_team_id')
                ->nullable()
                ->after('internship_batch_id')
                ->constrained('intern_teams')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->dropForeign(['intern_team_id']);
            $table->dropColumn(['intern_team_id']);
        });
    }
};
