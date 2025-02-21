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
            $table->id();
            $table->enum('variant', ['A', 'B', 'C', 'D']);
            $table->integer('task_number');
            $table->text('conditions'); // условия задачи
            $table->text('solution'); // ее правильное решение
            $table->text('answer'); //ответ после правильного выполнения
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
