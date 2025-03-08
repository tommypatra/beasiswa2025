<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rumah extends Model
{
    protected $guarded = ["id"];

    public function pilihanKepemilikanRumah()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'id');
    }

    public function pilihanMck()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'id');
    }

    public function pilihanListrik()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'id');
    }

    public function pilihanSumberAir()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'id');
    }

    public function pilihanSumberListrik()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
