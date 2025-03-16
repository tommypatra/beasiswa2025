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
        Schema::create('peserta_wawancaras', function (Blueprint $table) {
            $table->id();
            $table->decimal('nilai', 4, 2)->nullable();
            $table->smallInteger('status')->nullable(); //1 proses wawancara 2 wawancara selesai
            $table->foreignId('pewawancara_id')->nullable();
            $table->foreign('pewawancara_id')->references('id')->on('pewawancaras')->restrictOnDelete();
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
        Schema::dropIfExists('peserta_wawancaras');
    }
};
