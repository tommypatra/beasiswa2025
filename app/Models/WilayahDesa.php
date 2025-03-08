<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahDesa extends Model
{
    protected $guarded = ["id"];

    public function identitas()
    {
        return $this->hasOne(Identitas::class);
    }

    public function wilayahKecamatan()
    {
        return $this->belongsTo(WilayahKecamatan::class);
    }
}
