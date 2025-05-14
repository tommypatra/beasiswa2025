<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Predikat extends Model
{
    protected $guarded = ["id"];

    public function monitoring()
    {
        return $this->belongsTo(Monitoring::class);
    }
}
