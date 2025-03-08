<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syarat extends Model
{
    protected $guarded = ["id"];

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function uploadSyarat()
    {
        return $this->hasOne(UploadSyarat::class);
    }
}
