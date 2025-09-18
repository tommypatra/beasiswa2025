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

    public function wawancaraNilai()
    {
        return $this->hasMany(WawancaraNilai::class, 'pendaftar_id', 'pendaftar_id')
            ->whereColumn('pewawancara_id', 'pewawancara_id')
            ->with('soalWawancara');
    }

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
