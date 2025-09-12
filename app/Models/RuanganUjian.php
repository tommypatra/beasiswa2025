<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuanganUjian extends Model
{
    protected $guarded = ["id"];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
