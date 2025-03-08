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
        Schema::create('dokumentasi_surveis', function (Blueprint $table) {
            $table->id();
            $table->string('path', 150)->nullable();
            $table->foreignId('surveyor_id')->nullable();
            $table->foreign('surveyor_id')->references('id')->on('surveyors')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_surveis');
    }
};
