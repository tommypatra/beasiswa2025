<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikatorPenerima extends Model
{
    protected $guarded = ["id"];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function verifikatorLaporan()
    {
        return $this->belongsTo(VerifikatorLaporan::class);
    }
}
