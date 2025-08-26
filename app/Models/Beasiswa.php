<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    protected $guarded = ["id"];

    protected $appends = ['is_pendaftaran_aktif'];

    public function getIsPendaftaranAktifAttribute()
    {
        return now()->between($this->daftar_mulai, $this->daftar_selesai);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }

    public function pewawancara()
    {
        return $this->hasMany(Pewawancara::class);
    }

    public function verifikator()
    {
        return $this->hasMany(Verifikator::class);
    }

    public function verifikatorPendaftar()
    {
        return $this->hasManyThrough(VerifikatorPendaftar::class, Pendaftar::class, 'beasiswa_id', 'pendaftar_id');
    }

    public function syarat()
    {
        return $this->hasMany(Syarat::class);
    }

    public function soalWawancara()
    {
        return $this->belongsTo(SoalWawancara::class);
    }

    public function jenisBeasiswa()
    {
        return $this->belongsTo(JenisBeasiswa::class);
    }

    public function surveyor()
    {
        return $this->hasOne(Surveyor::class);
    }

    public function pesertaWawancara()
    {
        return $this->hasManyThrough(
            PesertaWawancara::class,
            Pewawancara::class,
            'beasiswa_id',    // foreign key di tabel pewawancaras
            'pewawancara_id', // foreign key di tabel peserta_wawancaras
            'id',             // local key di tabel beasiswas
            'id'              // local key di tabel pewawancaras
        )->whereHas('pendaftar.verifikatorPendaftar', function ($q) {
            $q->where('hasil', 1);
        });
    }
}
