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
        Schema::create('beasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150)->nullable();
            $table->string('syarat_tahun_angkatan_mahasiswa', 50)->nullable();
            $table->string('syarat_tahun_lulus_sma', 50)->nullable();
            $table->year('tahun')->nullable();
            $table->text('deskripsi')->nullable();
            $table->date('daftar_mulai')->nullable();
            $table->date('daftar_selesai')->nullable();
            $table->date('verifikasi_berkas_mulai')->nullable();
            $table->date('verifikasi_berkas_selesai')->nullable();
            $table->date('survei_lapangan_mulai')->nullable();
            $table->date('survei_lapangan_selesai')->nullable();
            $table->date('wawancara_mulai')->nullable();
            $table->date('wawancara_selesai')->nullable();
            $table->date('pengumuman_verifikasi_berkas')->nullable();
            $table->date('pengumuman_akhir')->nullable();
            $table->boolean('ada_wawancara')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->boolean('perlu_data_orang_tua')->default(false);
            $table->boolean('perlu_data_rumah')->default(false);
            $table->boolean('perlu_data_nilai_raport')->default(false);
            $table->boolean('perlu_data_pendidikan_akhir')->default(false);
            $table->foreignId('jenis_beasiswa_id');
            $table->foreign('jenis_beasiswa_id')->references('id')->on('jenis_beasiswas')->restrictOnDelete();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beasiswas');
    }
};
