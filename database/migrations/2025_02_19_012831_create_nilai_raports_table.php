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
        Schema::create('nilai_raports', function (Blueprint $table) {
            $table->id();
            $table->decimal('smt_1_nilai', 5, 2)->default(0);
            $table->integer('smt_1_peringkat')->nullable();
            $table->decimal('smt_2_nilai', 5, 2)->default(0);
            $table->integer('smt_2_peringkat')->nullable();
            $table->decimal('smt_3_nilai', 5, 2)->default(0);
            $table->integer('smt_3_peringkat')->nullable();
            $table->decimal('smt_4_nilai', 5, 2)->default(0);
            $table->integer('smt_4_peringkat')->nullable();
            $table->decimal('smt_5_nilai', 5, 2)->default(0);
            $table->integer('smt_5_peringkat')->nullable();
            $table->decimal('smt_6_nilai', 5, 2)->default(0);
            $table->integer('smt_6_peringkat')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();

            $table->boolean('verifikasi_lapangan_hasil')->nullable();
            $table->string('foto_raport_smt_1', 160)->nullable();
            $table->string('foto_raport_smt_2', 160)->nullable();
            $table->string('foto_raport_smt_3', 160)->nullable();
            $table->string('foto_raport_smt_4', 160)->nullable();
            $table->string('foto_raport_smt_5', 160)->nullable();
            $table->string('foto_raport_smt_6', 160)->nullable();
            $table->text('verifikasi_lapangan_catatan')->nullable();
            $table->decimal('verifikasi_lapangan_skor', 5, 2)->nullable();

            $table->timestamps();
            $table->unique(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_raports');
    }
};
