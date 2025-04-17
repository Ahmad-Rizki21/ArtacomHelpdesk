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
        Schema::create('ticket_backbones', function (Blueprint $table) {
            $table->id();
            $table->string('no_ticket')->unique();
            $table->foreignId('cid')->constrained('backbone_cids')->onDelete('cascade');
            $table->foreignId('lokasi_id')->constrained('backbone_cids')->onDelete('cascade');
            $table->text('extra_description')->nullable();
            $table->enum('status', ['OPEN', 'PENDING', 'CLOSED'])->default('OPEN');
            $table->timestamp('open_date')->useCurrent();
            $table->timestamp('pending_date')->nullable();
            $table->timestamp('closed_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_backbones');
    }
};
