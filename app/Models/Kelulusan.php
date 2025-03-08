<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelulusan extends Model
{
    protected $guarded = ["id"];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
