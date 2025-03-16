<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pewawancara extends Model
{
    protected $guarded = ["id"];

    public function wawancaraNilai()
    {
        return $this->hasMany(WawancaraNilai::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function pesertaWawancara()
    {
        return $this->hasMany(PesertaWawancara::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
