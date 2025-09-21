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
        Schema::create('penerimas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sk_penerima_id')->nullable();
            $table->foreign('sk_penerima_id')->references('id')->on('sk_penerimas')->restrictOnDelete();

            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();

            $table->foreignId('buku_rekening_id')->nullable();
            $table->foreign('buku_rekening_id')->references('id')->on('buku_rekenings')->restrictOnDelete();

            $table->text('keterangan')->nullable();

            $table->unique(['sk_penerima_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimas');
    }
};
