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
        Schema::create('jadwal_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->date('pengisian_mulai')->nullable();
            $table->date('pengisian_selesai')->nullable();
            $table->date('pengumuman')->nullable();
            $table->foreignId('sk_penerima_id')->nullable();
            $table->foreign('sk_penerima_id')->references('id')->on('sk_penerimas')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_monitorings');
    }
};
