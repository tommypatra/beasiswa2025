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
        Schema::create('pendidikan_akhirs', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 50)->nullable();
            $table->string('nama_sekolah', 100)->nullable();
            $table->string('foto_ijazah', 160)->nullable();
            $table->string('jenis', 50)->nullable();
            $table->enum('akreditasi', ['A', 'B', 'C'])->nullable();
            $table->year('tahun_lulus');
            $table->decimal('nilai_akhir_lulus', 5, 2)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->timestamps();

            $table->boolean('verifikasi_lapangan_hasil')->nullable();
            $table->text('verifikasi_lapangan_catatan')->nullable();
            $table->decimal('verifikasi_lapangan_skor', 5, 2)->nullable();

            $table->unique(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendidikan_akhirs');
    }
};
