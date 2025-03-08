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
        Schema::create('rumahs', function (Blueprint $table) {
            $table->id();
            $table->integer('luas_tanah')->nullable();
            $table->integer('luas_bangunan')->nullable();
            $table->integer('jumlah_orang_tinggal')->nullable();
            $table->foreignId('status_id');
            $table->foreign('status_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();
            $table->foreignId('bayar_listrik_id');
            $table->foreign('bayar_listrik_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();
            $table->foreignId('mck_id');
            $table->foreign('mck_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();
            $table->foreignId('sumber_air_id');
            $table->foreign('sumber_air_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();
            $table->foreignId('sumber_listrik_id');
            $table->foreign('sumber_listrik_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rumahs');
    }
};
