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
        Schema::table('task_submissions', function (Blueprint $table) 
        {
            $table->text('admin_feedback')->after('status')->nullable();

            $table->integer('marks')->after('admin_feedback')->nullable(); // Out of 100
            // OR
            $table->string('grade', 5)->after('marks')->nullable(); // A+, B, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('task_submissions', function (Blueprint $table) 
        {
            $table->dropColumn('admin_feedback');

            $table->dropColumn('marks'); // Out of 100
            // OR
            $table->dropColumn('grade'); // A+, B, etc.
        });
    }
};
