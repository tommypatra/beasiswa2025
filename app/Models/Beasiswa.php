<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    protected $guarded = ["id"];

    protected $appends = ['is_pendaftaran_aktif', 'is_verifikasi_berkas_aktif', 'is_wawancara_aktif', 'is_survei_aktif'];

    public function getIsWawancaraAktifAttribute()
    {
        if (!$this->wawancara_mulai || !$this->wawancara_selesai) {
            return false;
        }
        return today()->between($this->wawancara_mulai, $this->wawancara_selesai);
    }

    public function getIsSurveiAktifAttribute()
    {
        if (!$this->survei_lapangan_mulai || !$this->survei_lapangan_selesai) {
            return false;
        }
        return today()->between($this->survei_lapangan_mulai, $this->survei_lapangan_selesai);
    }

    public function getIsPendaftaranAktifAttribute()
    {
        if (!$this->daftar_mulai || !$this->daftar_selesai) {
            return false;
        }
        return today()->between($this->daftar_mulai, $this->daftar_selesai);
    }

    public function getIsVerifikasiBerkasAktifAttribute()
    {
        if (!$this->verifikasi_berkas_mulai || !$this->verifikasi_berkas_selesai) {
            return false;
        }
        return today()->between($this->verifikasi_berkas_mulai, $this->verifikasi_berkas_selesai);
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

    public function sesiUjian()
    {
        return $this->hasMany(SesiUjian::class);
    }

    public function jadwalUjian()
    {
        return $this->hasMany(JadwalUjian::class);
    }

    public function pengaturanUjian()
    {
        return $this->hasOne(PengaturanUjian::class);
    }

    public function verifikatorPendaftar()
    {
        return $this->hasManyThrough(VerifikatorPendaftar::class, Pendaftar::class, 'beasiswa_id', 'pendaftar_id');
    }

    public function syarat()
    {
        // return $this->hasMany(Syarat::class);
        return $this->hasMany(Syarat::class)->orderBy('beasiswa_id')->orderBy('urut')->orderBy('id');
    }

    public function soalWawancara()
    {
        return $this->belongsTo(SoalWawancara::class);
    }

    public function jenisBeasiswa()
    {
        return $this->belongsTo(JenisBeasiswa::class);
    }

    public function ruangUjian()
    {
        return $this->hasMany(RuangUjian::class);
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
