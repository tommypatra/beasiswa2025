<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $guarded = ["id"];

    public function programStudi()
    {
        return $this->hasMany(ProgramStudi::class);
    }
}
