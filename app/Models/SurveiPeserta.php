<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveiPeserta extends Model
{
    //
    protected $guarded = ["id"];

    public function surveyor()
    {
        return $this->belongsTo(Surveyor::class);
    }

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
