<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $guarded = ["id"];

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }

    public function sumberBiaya()
    {
        return $this->hasMany(SumberBiaya::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
