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
            Schema::create('backbone_cids', function (Blueprint $table) {
            $table->id();
            $table->string('cid')->unique();
            $table->string('lokasi')->nullable(); 
            $table->enum('jenis_isp', ['INDIBIZ', 'ASTINET', 'ICON PLUS', 'FIBERNET']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backbone_cids');
    }
};
