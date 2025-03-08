<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBeasiswa extends Model
{
    protected $guarded = ["id"];

    public function beasiswa()
    {
        return $this->hasOne(Beasiswa::class);
    }
}
