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
        Schema::table('ticket_backbones', function (Blueprint $table) {
            $table->text('action_description')->nullable()->after('extra_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_backbones', function (Blueprint $table) {
            $table->dropColumn('action_description');
        });
    }
};