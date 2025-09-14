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
        Schema::create('peserta_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id');
            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->restrictOnDelete();

            $table->foreignId('jadwal_ujian_id');
            $table->foreign('jadwal_ujian_id')->references('id')->on('jadwal_ujians')->restrictOnDelete();

            $table->unique(['pendaftar_id', 'jadwal_ujian_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_ujians');
    }
};
