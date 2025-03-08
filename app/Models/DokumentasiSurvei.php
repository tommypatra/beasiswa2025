<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumentasiSurvei extends Model
{
    protected $guarded = ["id"];

    public function surveyor()
    {
        return $this->belongsTo(Surveyor::class);
    }
}
