<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKegiatan extends Model
{
    protected $guarded = ["id"];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }


    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }


    public function tingkat()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'tingkat_id');
    }

    public function pjp()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'pjp_id');
    }
}
