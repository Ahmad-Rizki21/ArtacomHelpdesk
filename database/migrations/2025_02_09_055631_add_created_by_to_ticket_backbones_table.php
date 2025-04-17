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
        // Cek apakah kolom 'created_by' sudah ada
        if (!Schema::hasColumn('ticket_backbones', 'created_by')) {
            Schema::table('ticket_backbones', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable()->after('some_column'); // Ganti 'some_column' dengan kolom yang sesuai
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_backbones', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });
    }
};
