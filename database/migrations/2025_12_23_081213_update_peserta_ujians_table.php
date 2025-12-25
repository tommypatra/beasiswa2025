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
        Schema::table('peserta_ujians', function (Blueprint $table) {
            if (!Schema::hasColumn('peserta_ujians', 'nilai')) {
                $table->float('nilai', 8, 2)
                    ->nullable();
            }

            if (!Schema::hasColumn('peserta_ujians', 'status')) {
                $table->char('status', 1)
                    ->nullable()
                    ->comment('1=lulus,0=tidak lulus');
            }
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_ujians', function (Blueprint $table) {
            $table->dropColumn(['nilai', 'status']);
        });
    }
};
