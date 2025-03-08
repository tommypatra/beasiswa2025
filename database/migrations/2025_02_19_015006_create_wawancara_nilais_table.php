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
        Schema::create('wawancara_nilais', function (Blueprint $table) {
            $table->id();
            $table->integer('nilai')->nullable();
            $table->foreignId('pewawancara_id')->nullable();
            $table->foreign('pewawancara_id')->references('id')->on('pewawancaras')->restrictOnDelete();
            $table->foreignId('soal_wawancara_id')->nullable();
            $table->foreign('soal_wawancara_id')->references('id')->on('soal_wawancaras')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['pewawancara_id', 'soal_wawancara_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wawancara_nilais');
    }
};
