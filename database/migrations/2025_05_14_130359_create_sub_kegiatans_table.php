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
        Schema::create('sub_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable();
            $table->string('tingkat', 100)->nullable();
            $table->string('pjp', 100)->nullable();
            $table->text('bukti')->nullable();
            $table->tinyInteger('nilai')->nullable();
            $table->foreignId('kegiatan_id')->nullable();
            $table->foreign('kegiatan_id')->references('id')->on('kegiatans')->restrictOnDelete();


            $table->foreignId('tingkat_id')->nullable();
            $table->foreignId('pjp_id')->nullable();
            $table->foreign('tingkat_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();
            $table->foreign('pjp_id')->references('id')->on('referensi_pilihans')->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kegiatans');
    }
};
