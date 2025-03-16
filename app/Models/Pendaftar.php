<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    protected $guarded = ["id"];

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function kelulusan()
    {
        return $this->hasOne(Kelulusan::class);
    }

    public function uploadSyarat()
    {
        return $this->hasOne(UploadSyarat::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function pewawancara()
    {
        return $this->hasOne(Pewawancara::class);
    }

    public function surveyor()
    {
        return $this->hasOne(Surveyor::class);
    }

    public function verifikatorPendaftar()
    {
        return $this->hasOne(VerifikatorPendaftar::class);
    }

    public function pesertaWawancara()
    {
        return $this->hasMany(PesertaWawancara::class);
    }
}
