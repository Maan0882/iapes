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
         Schema::table('offer_letters', function (Blueprint $table) {
            //
            $table->string('university')
                ->required()
                ->after('application_id');
            $table->string('internship_position')
                ->nullable()
                ->after('university');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('offer_letters', function (Blueprint $table) {
            //
             $table->dropColumn(['university','internship_position']);

        });
    }
};
