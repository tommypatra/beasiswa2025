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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 50);
            $table->string('kartu_mahasiswa', 180)->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->foreignId('program_studi_id')->nullable();
            $table->foreign('program_studi_id')->references('id')->on('program_studis')->restrictOnDelete();
            $table->foreignId('sumber_biaya_id')->nullable();
            $table->foreign('sumber_biaya_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['nim']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
