<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkPenerima extends Model
{
    protected $guarded = ["id"];

    public function verifikatorLaporan()
    {
        return $this->hasMany(VerifikatorLaporan::class);
    }

    public function penerima()
    {
        return $this->belongsTo(Penerima::class);
    }
}
