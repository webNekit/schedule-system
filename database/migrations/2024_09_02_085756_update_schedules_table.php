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
        Schema::table('schedules', function (Blueprint $table) {
            // Проверка и удаление существующих столбцов
            if (Schema::hasColumn('schedules', 'subject_id')) {
                $table->dropForeign(['subject_id']);
                $table->dropColumn('subject_id');
            }

            if (Schema::hasColumn('schedules', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
                $table->dropColumn('teacher_id');
            }

            if (Schema::hasColumn('schedules', 'room_id')) {
                $table->dropForeign(['room_id']);
                $table->dropColumn('room_id');
            }

            if (Schema::hasColumn('schedules', 'semester_id')) {
                $table->dropForeign(['semester_id']);
                $table->dropColumn('semester_id');
            }

            // Добавление новых столбцов, только если они не существуют
            if (!Schema::hasColumn('schedules', 'workday_id')) {
                $table->foreignId('workday_id')->constrained('workdays')->onDelete('cascade'); // Внешний ключ на workday
            }

            if (!Schema::hasColumn('schedules', 'department_id')) {
                $table->foreignId('department_id')->constrained('departments')->onDelete('cascade'); // Внешний ключ на кафедру
            }

            if (!Schema::hasColumn('schedules', 'group_id')) {
                $table->foreignId('group_id')->constrained('groups')->onDelete('cascade'); // Внешний ключ на группу
            }

            if (!Schema::hasColumn('schedules', 'lesson_id')) {
                $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade'); // Внешний ключ на lesson
            }

            if (!Schema::hasColumn('schedules', 'is_active')) {
                $table->boolean('is_active')->default(false); // Флаг активности расписания
            }

            if (!Schema::hasColumn('schedules', 'is_archive')) {
                $table->boolean('is_archive')->default(true); // Флаг архивации расписания
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'subject_id')) {
                $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // Внешний ключ на дисциплину
            }

            if (!Schema::hasColumn('schedules', 'teacher_id')) {
                $table->foreignId('teacher_id')->constrained()->onDelete('cascade'); // Внешний ключ на преподавателя
            }

            if (!Schema::hasColumn('schedules', 'room_id')) {
                $table->foreignId('room_id')->constrained()->onDelete('cascade'); // Внешний ключ на кабинет
            }

            if (!Schema::hasColumn('schedules', 'semester_id')) {
                $table->foreignId('semester_id')->constrained()->onDelete('cascade'); // Внешний ключ на семестр
            }

            // Удаляем новые столбцы
            if (Schema::hasColumn('schedules', 'workday_id')) {
                $table->dropForeign(['workday_id']);
                $table->dropColumn('workday_id');
            }

            if (Schema::hasColumn('schedules', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }

            if (Schema::hasColumn('schedules', 'group_id')) {
                $table->dropForeign(['group_id']);
                $table->dropColumn('group_id');
            }

            if (Schema::hasColumn('schedules', 'lesson_id')) {
                $table->dropForeign(['lesson_id']);
                $table->dropColumn('lesson_id');
            }

            if (Schema::hasColumn('schedules', 'is_active')) {
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('schedules', 'is_archive')) {
                $table->dropColumn('is_archive');
            }
        });
    }
};
