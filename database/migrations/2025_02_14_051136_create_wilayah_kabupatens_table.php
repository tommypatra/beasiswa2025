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
        Schema::create('wilayah_kabupatens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilayah_provinsi_id');
            $table->string('kodewilayah_prov', 4)->nullable();
            $table->string('kode', 4)->nullable();
            $table->string('nama', 100)->nullable();
            $table->foreign('wilayah_provinsi_id')->references('id')->on('wilayah_provinsis')->restrictOnDelete();
            $table->string('keterangan', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah_kabupatens');
    }
};
