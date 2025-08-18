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
        Schema::create('verifikator_penerima', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable();
            $table->text('catatan')->nullable();
            $table->tinyInteger('nilai')->nullable();

            $table->foreignId('verifikator_laporan_id')->nullable();
            $table->foreign('verifikator_laporan_id')->references('id')->on('verifikator_laporans')->restrictOnDelete();
            $table->foreignId('laporan_id')->nullable();
            $table->foreign('laporan_id')->references('id')->on('laporans')->restrictOnDelete();
            $table->unique(['verifikator_laporan_id', 'laporan_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikator_penerimas');
    }
};
