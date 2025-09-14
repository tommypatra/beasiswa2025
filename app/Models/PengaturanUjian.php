<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanUjian extends Model
{
    protected $guarded = ["id"];

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }
}
