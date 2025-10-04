<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $guarded = ["id"];


    public function ruanganUjian()
    {
        return $this->hasMany(RuanganUjian::class);
    }
}
