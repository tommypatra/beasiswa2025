<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surveyor extends Model
{
    protected $guarded = ["id"];

    public function dokumentasiSurvei()
    {
        return $this->hasMany(DokumentasiSurvei::class);
    }

    public function uploadSyarat()
    {
        return $this->hasOne(UploadSyarat::class);
    }
}
