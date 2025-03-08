<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalWawancara extends Model
{
    protected $guarded = ["id"];

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function wawancaraNilai()
    {
        return $this->hasMany(WawancaraNilai::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
