<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuRekening extends Model
{
    protected $guarded = ["id"];


    public function penerima()
    {
        return $this->belongsTo(Penerima::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
