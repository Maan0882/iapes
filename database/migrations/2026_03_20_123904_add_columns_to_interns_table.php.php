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
        Schema::table('interns', function (Blueprint $table) {
            //
            $table->string('project_name')
                ->nullable()
                ->after('joining_date');
            $table->text('project_description')
                ->nullable()
                ->after('project_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('interns', function (Blueprint $table) {
            //
             $table->dropColumn(['project_name','project_description']);

        });
    }
};
