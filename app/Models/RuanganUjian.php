<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuanganUjian extends Model
{
    protected $guarded = ["id"];

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function jadwalUjian()
    {
        return $this->hasMany(JadwalUjian::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
