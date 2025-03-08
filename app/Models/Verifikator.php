<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifikator extends Model
{
    protected $guarded = ["id"];

    public function uploadSyarat()
    {
        return $this->hasOne(UploadSyarat::class);
    }

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
