<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikatorPendaftar extends Model
{
    //
    protected $guarded = ["id"];

    public function verifikator()
    {
        return $this->belongsTo(Verifikator::class);
    }


    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
