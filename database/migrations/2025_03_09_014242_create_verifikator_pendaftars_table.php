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
        Schema::create('verifikator_pendaftars', function (Blueprint $table) {
            $table->id();
            $table->boolean('hasil')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('verifikator_id')->nullable();
            $table->foreign('verifikator_id')->references('id')->on('verifikators')->restrictOnDelete();
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
        Schema::dropIfExists('verifikator_pendaftars');
    }
};
