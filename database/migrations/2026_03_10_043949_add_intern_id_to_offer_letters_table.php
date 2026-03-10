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
        Schema::table('offer_letters', function (Blueprint $table) {
            // Track if the offer is accepted (for the toggle)
            $table->boolean('is_accepted')->default(false); 
            
            // Link to the separate Intern record we created
            $table->foreignId('intern_id')
                ->nullable()
                ->constrained('interns')
                ->onDelete('set null')
                ->after('is_accepted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offer_letters', function (Blueprint $table) {
            $table->dropForeign(['intern_id']);
            $table->dropColumn(['is_accepted', 'intern_id']);
        });
    }
};
