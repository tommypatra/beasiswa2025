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
            $table->string('jabatan', 100)->nullable();
            $table->string('prestasi', 100)->nullable();
            $table->text('bukti')->nullable();
            $table->tinyInteger('nilai')->nullable();
            $table->foreignId('monitoring_id')->nullable();
            $table->foreign('monitoring_id')->references('id')->on('monitorings')->restrictOnDelete();
            $table->foreignId('kegiatan_id')->nullable();
            $table->foreign('kegiatan_id')->references('id')->on('kegiatans')->restrictOnDelete();
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
