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
        Schema::create('acaras', function (Blueprint $table) {
            $table->id();
            $table->string('nama_acara');
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->date('tgl_mulai');
            $table->date('tgl_akhir');
            $table->date('tgl_ditutup')->nullable();
            $table->date('tgl_early')->nullable();
            $table->string('lokasi');
            $table->string('status');
            $table->integer('harga_early');
            $table->integer('harga_reguler');
            $table->integer('harga_spesialis');
            $table->string('wa_link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acaras');
    }
};
