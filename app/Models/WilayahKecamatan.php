<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahKecamatan extends Model
{
    protected $guarded = ["id"];

    public function wilayahDesa()
    {
        return $this->hasMany(WilayahDesa::class);
    }

    public function wilayahKabupaten()
    {
        return $this->belongsTo(WilayahKabupaten::class);
    }
}
