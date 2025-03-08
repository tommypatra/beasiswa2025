<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identitas extends Model
{
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wilayahDesa()
    {
        return $this->belongsTo(WilayahDesa::class);
    }
}
