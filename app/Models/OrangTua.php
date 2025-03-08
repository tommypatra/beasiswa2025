<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pekerjaanBapak()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'pekerjaan_bapak_id');
    }

    public function pekerjaanIbu()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'pekerjaan_ibu_id');
    }

    public function pendapatanBapak()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'pendapatan_bapak_id');
    }

    public function pendapatanIbu()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'pendapatan_ibu_id');
    }

    public function pendidikanBapak()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'pendidikan_bapak_id');
    }

    public function pendidikanIbu()
    {
        return $this->belongsTo(ReferensiPilihan::class, 'pendidikan_ibu_id');
    }
}
