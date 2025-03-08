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
        Schema::create('soal_wawancaras', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor')->nullable();
            $table->text('soal')->nullable();
            $table->integer('persentase_nilai')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreignId('beasiswa_id')->nullable();
            $table->foreign('beasiswa_id')->references('id')->on('beasiswas')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_wawancaras');
    }
};
