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
        Schema::create('materi_ujians', function (Blueprint $table) {
            $table->id();
            $table->integer('urut');
            $table->string('ujian', 170);
            $table->string('keterangan', 170)->nullable();
            $table->foreignId('beasiswa_id');
            $table->foreign('beasiswa_id')->references('id')->on('beasiswas')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_ujians');
    }
};
