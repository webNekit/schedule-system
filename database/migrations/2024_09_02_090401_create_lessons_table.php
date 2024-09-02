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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            for ($i = 1; $i <= 7; $i++) {
                $table->foreignId("{$i}_subject_id")->nullable()->constrained('subjects')->onDelete('set null'); // Дисциплина
                $table->foreignId("{$i}_teacher_id")->nullable()->constrained('teachers')->onDelete('set null'); // Преподаватель
                $table->foreignId("{$i}_room_id")->nullable()->constrained('rooms')->onDelete('set null'); // Кабинет
                $table->boolean("{$i}_is_empty")->default(false); // Флаг отсутствия пары
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
