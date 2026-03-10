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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('task_id');
            
            $table->string('title');
            $table->text('description');
            $table->enum('assigned_to', ['intern', 'team', 'batch']);
             // 👇 THIS is where intern_id belongs
            $table->foreignId('intern_id')
                ->constrained('interns')
                ->onDelete('cascade');

            $table->date('due_date');
            
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
