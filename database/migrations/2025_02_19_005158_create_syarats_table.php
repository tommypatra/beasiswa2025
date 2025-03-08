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
        Schema::create('syarats', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable();
            $table->string('jenis', 100)->nullable();
            $table->string('contoh', 180)->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_wajib')->default(true);
            $table->boolean('is_aktif')->default(true);
            $table->foreignId('beasiswa_id');
            $table->foreign('beasiswa_id')->references('id')->on('beasiswas')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['nama', 'beasiswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syarats');
    }
};
