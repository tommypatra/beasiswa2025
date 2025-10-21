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
        Schema::create('survei_pesertas', function (Blueprint $table) {
            $table->id();
            $table->string('hasil', 50)->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('tag')->nullable();
            $table->decimal('total_skor', 5, 2)->nullable();
            $table->foreignId('surveyor_id')->nullable();
            $table->foreign('surveyor_id')->references('id')->on('surveyors')->restrictOnDelete();
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
        Schema::dropIfExists('survei_pesertas');
    }
};
