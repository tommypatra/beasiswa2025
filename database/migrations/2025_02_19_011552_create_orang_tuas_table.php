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
        Schema::create('orang_tuas', function (Blueprint $table) {
            $table->id();
            $table->string('bapak_nama', 100)->nullable();
            $table->string('ibu_nama', 100)->nullable();
            $table->boolean('status_hidup_bapak_kandung')->default(true);
            $table->boolean('status_hidup_ibu_kandung')->default(true);
            $table->foreignId('pekerjaan_bapak_id')->nullable();
            $table->foreignId('pekerjaan_ibu_id')->nullable();
            $table->foreignId('pendidikan_bapak_id')->nullable();
            $table->foreignId('pendidikan_ibu_id')->nullable();
            $table->foreignId('pendapatan_bapak_id')->nullable();
            $table->foreignId('pendapatan_ibu_id')->nullable();
            $table->foreign('pekerjaan_ibu_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();
            $table->foreign('pendidikan_bapak_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();
            $table->foreign('pendidikan_ibu_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();
            $table->foreign('pendapatan_bapak_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();
            $table->foreign('pendapatan_ibu_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();

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
        Schema::dropIfExists('orang_tuas');
    }
};
