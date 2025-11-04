<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $guarded = ["id"];

    public function subKegiatan()
    {
        return $this->hasMany(SubKegiatan::class) > orderBy('urut', 'asc')->orderBy('id', 'asc');
    }

    public function monitoring()
    {
        return $this->belongsTo(Monitoring::class);
    }
}
