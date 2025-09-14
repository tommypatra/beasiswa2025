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
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->foreignId('ruangan_id');
            $table->foreign('ruangan_id')->references('id')->on('ruangans')->restrictOnDelete();
            $table->foreignId('beasiswa_id');
            $table->foreign('beasiswa_id')->references('id')->on('beasiswas')->restrictOnDelete();

            $table->unique(['beasiswa_id', 'ruangan_id', 'jam_mulai']);
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
