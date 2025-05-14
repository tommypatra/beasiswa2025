<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rumah extends Model
{
    protected $guarded = ["id"];

    public function pilihanKepemilikanRumah()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'status_id');
    }

    public function pilihanMck()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'mck_id');
    }

    public function pilihanListrik()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'bayar_listrik_id');
    }

    public function pilihanSumberAir()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'sumber_air_id');
    }

    public function pilihanSumberListrik()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'sumber_listrik_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
