<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    //------------------------------- definisikan relasi

    public function identitas()
    {
        return $this->hasOne(Identitas::class);
    }

    public function userRole()
    {
        return $this->hasMany(UserRole::class);
    }

    public function beasiswa()
    {
        return $this->hasOne(Beasiswa::class);
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function nilaiRaport()
    {
        return $this->hasOne(NilaiRaport::class);
    }

    public function orangTua()
    {
        return $this->hasOne(OrangTua::class);
    }

    public function pendidikanAkhir()
    {
        return $this->hasOne(PendidikanAkhir::class);
    }

    public function pewawancara()
    {
        return $this->hasOne(Pewawancara::class);
    }

    public function prestasi()
    {
        return $this->hasMany(Prestasi::class);
    }

    public function verifikator()
    {
        return $this->hasMany(Verifikator::class);
    }

    public function rumah()
    {
        return $this->hasOne(Rumah::class);
    }

    public function soalWawancara()
    {
        return $this->hasMany(SoalWawancara::class);
    }
}
