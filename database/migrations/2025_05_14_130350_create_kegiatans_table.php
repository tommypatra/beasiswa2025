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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable();
            $table->decimal('nilai_minimal', 5, 2)->nullable();
            $table->smallInteger('urut')->nullable();
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
        Schema::dropIfExists('kegiatans');
    }
};
