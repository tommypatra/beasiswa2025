<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkPenerima extends Model
{
    protected $guarded = ["id"];

    public function verifikatorLaporan()
    {
        return $this->hasMany(VerifikatorLaporan::class);
    }

    public function penerima()
    {
        return $this->hasMany(Penerima::class);
    }

    public function jadwalMonitoring()
    {
        return $this->hasMany(JadwalMonitoring::class);
    }

    public function monitoring()
    {
        return $this->belongsTo(Monitoring::class);
    }
}
