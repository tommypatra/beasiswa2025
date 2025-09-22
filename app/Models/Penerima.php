<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerima extends Model
{
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }

    public function bukuRekening()
    {
        return $this->belongsTo(BukuRekening::class);
    }

    public function skPenerima()
    {
        return $this->belongsTo(SkPenerima::class);
    }
}
