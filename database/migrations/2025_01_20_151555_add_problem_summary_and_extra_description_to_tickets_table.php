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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('problem_summary')->nullable(); // Menambahkan kolom baru
            $table->text('extra_description')->nullable(); // Menambahkan kolom baru
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('problem_summary'); // Menghapus kolom saat rollback
            $table->dropColumn('extra_description'); // Menghapus kolom saat rollback
        });
    }
};
