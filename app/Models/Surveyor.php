<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surveyor extends Model
{
    protected $guarded = ["id"];

    public function dokumentasiSurvei()
    {
        return $this->hasMany(DokumentasiSurvei::class);
    }

    public function surveiPeserta()
    {
        return $this->hasMany(SurveiPeserta::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }
}
