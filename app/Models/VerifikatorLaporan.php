<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikatorLaporan extends Model
{
    protected $guarded = ["id"];

    public function skPenerima()
    {
        return $this->belongsTo(SkPenerima::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifikatorPenerima()
    {
        return $this->hasMany(VerifikatorPenerima::class);
    }
}
