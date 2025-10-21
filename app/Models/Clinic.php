<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clinic extends Model
{
    protected $primaryKey = 'clinic_id';
    protected $fillable = ['name', 'address', 'phone', 'email', 'description'];

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class, 'clinic_id', 'clinic_id');
    }
}
