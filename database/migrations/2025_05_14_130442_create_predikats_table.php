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
        Schema::create('predikats', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('nilai_minimal')->nullable();
            $table->tinyInteger('nilai_maksimal')->nullable();
            $table->string('predikat', 100)->nullable();
            $table->foreignId('monitoring_id')->nullable();
            $table->foreign('monitoring_id')->references('id')->on('monitorings')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predikats');
    }
};
