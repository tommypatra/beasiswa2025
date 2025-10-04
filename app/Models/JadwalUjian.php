<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalUjian extends Model
{
    protected $guarded = ["id"];

    public function ruanganUjian()
    {
        return $this->belongsTo(RuanganUjian::class);
    }

    public function sesiUjian()
    {
        return $this->belongsTo(SesiUjian::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }
}
