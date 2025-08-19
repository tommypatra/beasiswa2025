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
        Schema::create('upload_syarats', function (Blueprint $table) {
            $table->id();
            $table->boolean('verifikasi_berkas_hasil')->nullable();
            $table->text('verifikasi_berkas_catatan')->nullable();
            $table->boolean('verifikasi_lapangan_hasil')->nullable();
            $table->text('verifikasi_lapangan_catatan')->nullable();
            $table->decimal('verifikasi_berkas_skor', 5, 2)->nullable();
            $table->decimal('verifikasi_lapangan_skor', 5, 2)->nullable();
            $table->string('dokumen', 160)->nullable();
            $table->foreignId('pendaftar_id')->nullable();
            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->restrictOnDelete();
            $table->foreignId('syarat_id')->nullable();
            $table->foreign('syarat_id')->references('id')->on('syarats')->restrictOnDelete();
            $table->unique(['pendaftar_id', 'syarat_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_syarats');
    }
};
