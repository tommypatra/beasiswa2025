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
        Schema::create('kelulusans', function (Blueprint $table) {
            $table->id();
            $table->decimal('nilai_survei', 5, 2)->nullable();
            $table->decimal('nilai_cbt', 5, 2)->nullable();
            $table->decimal('nilai_berkas', 5, 2)->nullable();
            $table->decimal('nilai_orang_tua', 5, 2)->nullable();
            $table->decimal('nilai_raport', 5, 2)->nullable();
            $table->decimal('nilai_pendidikan_akhir', 5, 2)->nullable();
            $table->decimal('nilai_rumah', 5, 2)->nullable();
            $table->decimal('nilai_wawancara', 5, 2)->nullable();
            $table->decimal('nilai_ekonomi', 5, 2)->nullable();
            $table->decimal('nilai_pendidikan', 5, 2)->nullable();
            $table->boolean('is_lulus')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('pendaftar_id')->nullable();
            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['pendaftar_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelulusans');
    }
};
