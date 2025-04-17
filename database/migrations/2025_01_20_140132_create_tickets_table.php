<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis
            $table->integer('no')->unique(); // Pastikan kolom 'no' unik
            $table->string('service'); // Kolom Layanan
            $table->string('ticket_number')->unique(); // Pastikan kolom 'ticket_number' unik
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Kolom Id Pelanggan
            $table->timestamp('report_date')->useCurrent(); // Kolom Report Date
            $table->string('status')->default('OPEN'); // Kolom Status
            $table->timestamp('pending_clock')->nullable(); // Kolom Pending Clock
            $table->timestamp('closed_date')->nullable(); // Kolom Closed Date
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}