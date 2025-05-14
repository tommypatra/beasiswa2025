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

    public function monitoring()
    {
        return $this->belongsTo(Monitoring::class);
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }
}
