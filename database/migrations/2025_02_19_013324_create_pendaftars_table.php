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
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran', 100);
            $table->string('url_id', 100)->nullable();
            $table->boolean('is_batal')->default(0);
            $table->boolean('is_finalisasi')->default(0);
            $table->text('alasan')->nullable();
            $table->foreignId('mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->restrictOnDelete();
            $table->foreignId('beasiswa_id');
            $table->foreign('beasiswa_id')->references('id')->on('beasiswas')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['url_id']);
            $table->unique(['mahasiswa_id', 'beasiswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
