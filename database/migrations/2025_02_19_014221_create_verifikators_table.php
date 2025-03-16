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
        Schema::create('verifikators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreignId('beasiswa_id')->nullable();
            $table->foreign('beasiswa_id')->references('id')->on('beasiswas')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'beasiswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikators');
    }
};
