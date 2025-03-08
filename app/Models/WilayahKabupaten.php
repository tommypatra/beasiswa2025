<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahKabupaten extends Model
{
    protected $guarded = ["id"];

    public function wilayahKecamatan()
    {
        return $this->hasMany(WilayahKecamatan::class);
    }

    public function wilayahProvinsi()
    {
        return $this->belongsTo(WilayahProvinsi::class);
    }
}
