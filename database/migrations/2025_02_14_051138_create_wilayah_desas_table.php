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
        Schema::create('wilayah_desas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilayah_kecamatan_id');
            $table->string('kodewilayah_prov', 4)->nullable();
            $table->string('kodewilayah_kab', 4)->nullable();
            $table->string('kodewilayah_kec', 4)->nullable();
            $table->string('kode', 4)->nullable();
            $table->string('desa', 100)->nullable();
            $table->foreign('wilayah_kecamatan_id')->references('id')->on('wilayah_kecamatans')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah_desas');
    }
};
