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
        Schema::table('workdays', function (Blueprint $table) {
            $table->boolean('is_active')->default(false)->after('date'); // Добавляет поле is_active после поля date
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workdays', function (Blueprint $table) {
            $table->dropColumn('is_active'); // Удаляет поле is_active
        });
    }
};
