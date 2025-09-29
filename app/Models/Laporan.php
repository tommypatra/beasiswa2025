<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $guarded = ["id"];

    public function verifikator()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function penerima()
    {
        return $this->belongsTo(Penerima::class);
    }

    public function subKegiatan()
    {
        return $this->belongsTo(SubKegiatan::class);
    }

    public function verifikatorPenerima()
    {
        return $this->hasOne(verifikatorPenerima::class);
    }
}
