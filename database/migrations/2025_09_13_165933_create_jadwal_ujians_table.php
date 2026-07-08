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
        Schema::create('jadwal_ujians', function (Blueprint $table) {
            $table->id();
            $table->integer('sesi');
            $table->date('tanggal');
            $table->foreignId('sesi_ujian_id');
            $table->foreign('sesi_ujian_id')->references('id')->on('sesi_ujians')->restrictOnDelete();
            $table->foreignId('ruangan_ujian_id');
            $table->foreign('ruangan_ujian_id')->references('id')->on('ruangan_ujians')->restrictOnDelete();
            $table->foreignId('beasiswa_id');
            $table->foreign('beasiswa_id')->references('id')->on('beasiswas')->restrictOnDelete();

            $table->unique(['beasiswa_id', 'sesi']);
            // $table->unique(['beasiswa_id', 'tanggal', 'ruangan_ujian_id', 'sesi_ujian_id']);
            $table->unique(
                ['beasiswa_id', 'tanggal', 'ruangan_ujian_id', 'sesi_ujian_id'],
                'uk_jadwal_ujian'
            );            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_ujians');
    }
};
