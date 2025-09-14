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
        Schema::create('pengaturan_ujians', function (Blueprint $table) {
            $table->id();
            $table->integer('peserta_per_ruangan')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('cetak_kartu_ujian')->nullable();
            $table->foreignId('beasiswa_id');
            $table->foreign('beasiswa_id')->references('id')->on('beasiswas')->restrictOnDelete();

            $table->unique(['beasiswa_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_ujians');
    }
};
