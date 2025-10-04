<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiUjian extends Model
{
    protected $guarded = ["id"];

    public function jadwalUjian()
    {
        return $this->hasMany(JadwalUjian::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }
}
