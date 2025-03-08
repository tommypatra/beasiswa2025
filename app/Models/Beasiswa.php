<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }

    public function syarat()
    {
        return $this->hasMany(Syarat::class);
    }

    public function soalWawancara()
    {
        return $this->belongsTo(SoalWawancara::class);
    }

    public function jenisBeasiswa()
    {
        return $this->belongsTo(JenisBeasiswa::class);
    }
}
