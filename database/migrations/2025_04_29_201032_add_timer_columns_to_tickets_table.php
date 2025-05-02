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
            // Tambahkan kolom open_time_seconds untuk menyimpan total waktu (dalam detik) saat status OPEN
            $table->integer('open_time_seconds')->nullable()->default(0)->after('pending_clock');
            
            // Tambahkan kolom pending_time_seconds untuk menyimpan total waktu (dalam detik) saat status PENDING
            $table->integer('pending_time_seconds')->nullable()->default(0)->after('open_time_seconds');
            
            // Tambahkan kolom last_status_change_at untuk mencatat kapan terakhir status berubah
            $table->timestamp('last_status_change_at')->nullable()->after('closed_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('open_time_seconds');
            $table->dropColumn('pending_time_seconds');
            $table->dropColumn('last_status_change_at');
        });
    }
};