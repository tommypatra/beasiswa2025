<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pewawancara extends Model
{
    protected $guarded = ["id"];

    public function wawancaraNilai()
    {
        return $this->hasMany(WawancaraNilai::class);
    }

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
