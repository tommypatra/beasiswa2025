<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifikator extends Model
{
    protected $guarded = ["id"];

    public function uploadSyarat()
    {
        return $this->hasOne(UploadSyarat::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifikatorPendaftar()
    {
        return $this->hasMany(VerifikatorPendaftar::class);
    }

    public function pesertaWawancara()
    {
        return $this->hasMany(PesertaWawancara::class);
    }
}
