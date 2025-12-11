<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaUjian extends Model
{
    protected $guarded = ["id"];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function jadwalUjian()
    {
        return $this->belongsTo(JadwalUjian::class);
    }
}
