<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WawancaraNilai extends Model
{
    protected $guarded = ["id"];

    public function pewawancara()
    {
        return $this->belongsTo(Pewawancara::class);
    }

    public function soalWawancara()
    {
        return $this->belongsTo(SoalWawancara::class);
    }
}
