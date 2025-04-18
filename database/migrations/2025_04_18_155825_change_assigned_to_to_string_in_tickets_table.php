<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['assigned_to']);
            // Ubah kolom menjadi string
            $table->string('assigned_to')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Kembalikan ke foreignId
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null')->change();
        });
    }
};