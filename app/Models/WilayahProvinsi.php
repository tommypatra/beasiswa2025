<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahProvinsi extends Model
{
    protected $guarded = ["id"];

    public function wilayahKabupaten()
    {
        return $this->hasMany(WilayahKabupaten::class);
    }
}
