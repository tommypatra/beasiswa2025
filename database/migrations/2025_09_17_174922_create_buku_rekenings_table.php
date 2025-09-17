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
        Schema::create('buku_rekenings', function (Blueprint $table) {
            $table->id();

            $table->string('bank', 160);
            $table->string('nama_pemilik', 160);
            $table->string('nomor', 160);
            $table->string('foto_buku', 160);
            $table->date('tanggal_pembuatan');
            $table->boolean('is_aktif')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();

            $table->unique(['user_id', 'is_aktif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_rekenings');
    }
};
