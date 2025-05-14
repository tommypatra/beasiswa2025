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
        Schema::create('sk_penerimas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable();
            $table->string('nomor_sk', 100)->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('ttd_jabatan', 100)->nullable();
            $table->string('ttd_nama', 100)->nullable();
            $table->foreignId('monitoring_id')->nullable();
            $table->foreign('monitoring_id')->references('id')->on('monitorings')->restrictOnDelete();
            $table->unique(['nama']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk_penerimas');
    }
};
