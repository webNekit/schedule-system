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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // Внешний ключ на дисциплину
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade'); // Внешний ключ на преподавателя
            $table->foreignId('room_id')->constrained()->onDelete('cascade'); // Внешний ключ на кабинет
            $table->foreignId('group_id')->constrained()->onDelete('cascade'); // Внешний ключ на группу
            $table->foreignId('semester_id')->constrained()->onDelete('cascade'); // Внешний ключ на семестр
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
