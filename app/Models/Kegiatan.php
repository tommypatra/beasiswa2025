<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $guarded = ["id"];

    public function subKegiatan()
    {
        return $this->hasMany(SubKegiatan::class);
    }
}
