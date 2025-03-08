<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadSyarat extends Model
{
    protected $guarded = ["id"];

    public function syarat()
    {
        return $this->belongsTo(Syarat::class);
    }

    public function surveyor()
    {
        return $this->belongsTo(Surveyor::class);
    }

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(Verifikator::class);
    }
}
