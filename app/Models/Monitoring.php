<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $guarded = ["id"];

    public function predikat()
    {
        return $this->hasMany(Predikat::class);
    }

    public function subKegiatan()
    {
        return $this->hasMany(SubKegiatan::class);
    }
}
