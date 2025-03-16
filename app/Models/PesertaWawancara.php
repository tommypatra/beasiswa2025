<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaWawancara extends Model
{
    //
    protected $guarded = ["id"];

    public function pewawancara()
    {
        return $this->belongsTo(Pewawancara::class);
    }


    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
