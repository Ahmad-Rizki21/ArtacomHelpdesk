<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_backbone_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_backbone_id')->constrained('ticket_backbones')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('action_type');
            $table->text('description');
            $table->string('status');
            $table->timestamps();
        });
        
        // Add soft delete to ticket_backbones table
        Schema::table('ticket_backbones', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_backbone_actions');
        
        Schema::table('ticket_backbones', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};