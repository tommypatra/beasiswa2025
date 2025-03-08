<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = ["id"];

    public function userRole()
    {
        return $this->hasMany(UserRole::class);
    }
}
