<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalMonitoring extends Model
{
    protected $guarded = ["id"];

    public function skPenerima()
    {
        return $this->belongsTo(SkPenerima::class);
    }
}
