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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();

            $table->boolean('verifikasi_hasil')->nullable();
            $table->decimal('verifikasi_skor', 5, 2)->nullable();
            $table->string('verifikasi_catatan', 180)->nullable();
            $table->string('keterangan', 180)->nullable();

            $table->boolean('is_kirim')->nullable();
            $table->enum('path_jenis', ['url', 'link'])->nullable();
            $table->string('path', 180)->nullable();
            $table->foreignId('verifikator_id')->nullable();
            $table->foreign('verifikator_id')->references('id')->on('user_roles')->restrictOnDelete();
            $table->foreignId('penerima_id')->nullable();
            $table->foreign('penerima_id')->references('id')->on('penerimas')->restrictOnDelete();
            $table->foreignId('sub_kegiatan_id')->nullable();
            $table->foreign('sub_kegiatan_id')->references('id')->on('sub_kegiatans')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
