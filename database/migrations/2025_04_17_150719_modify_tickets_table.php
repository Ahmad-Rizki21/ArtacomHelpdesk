<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTicketsTable extends Migration
{
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Jika ingin membuat kolom 'no' nullable
            $table->integer('no')->nullable()->change();

            // Atau jika ingin menghapus kolom 'no'
            // $table->dropColumn('no');

            // Atau jika ingin mengubah definisi kolom 'no'
            // $table->integer('no')->unique()->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Kembalikan perubahan jika diperlukan
            // Contoh: $table->integer('no')->unique()->change();
        });
    }
}