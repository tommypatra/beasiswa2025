<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $guarded = ["id"];

    public function predikat()
    {
        return $this->hasMany(Predikat::class);
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class)->orderBy('urut', 'asc')
            ->orderBy('id', 'asc');
    }

    public function skPenerima()
    {
        return $this->hasMany(SkPenerima::class);
    }
}
