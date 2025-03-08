<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferensiPilihan extends Model
{
    protected $guarded = ["id"];

    public function sumberListrik()
    {
        return $this->belongsTo(SumberListrik::class);
    }

    public function rumahStatus()
    {
        return $this->belongsTo(Rumah::class, 'status_id');
    }

    public function rumahListrik()
    {
        return $this->belongsTo(Rumah::class, 'listrik_id');
    }

    public function rumahMck()
    {
        return $this->belongsTo(Rumah::class, 'mck_id');
    }

    public function rumahSumberAir()
    {
        return $this->belongsTo(Rumah::class, 'sumber_air_id');
    }

    public function rumaSumberListrik()
    {
        return $this->belongsTo(Rumah::class, 'sumber_listrik_id');
    }

    public function pekerjaanBapak()
    {
        return $this->belongsTo(OrangTua::class, 'id');
    }

    public function pekerjaanIbu()
    {
        return $this->belongsTo(OrangTua::class, 'id');
    }

    public function pendapatanBapak()
    {
        return $this->belongsTo(OrangTua::class, 'id');
    }

    public function pendapatanIbu()
    {
        return $this->belongsTo(OrangTua::class, 'id');
    }

    public function pendidikanBapak()
    {
        return $this->belongsTo(OrangTua::class, 'id');
    }

    public function pendidikanIbu()
    {
        return $this->belongsTo(OrangTua::class, 'id');
    }
}
