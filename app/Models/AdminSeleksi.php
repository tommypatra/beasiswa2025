<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSeleksi extends Model
{
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function pendaftar()
    {
        return $this->hasManyThrough(
            Pendaftar::class,
            Beasiswa::class,
            'id',           // foreign key di beasiswas
            'beasiswa_id',  // foreign key di pendaftars
            'beasiswa_id',  // local key di admin_seleksis
            'id'            // local key di beasiswas
        );
    }
}
