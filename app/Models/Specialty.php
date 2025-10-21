<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specialty extends Model
{
    protected $primaryKey = 'specialty_id';
    protected $fillable = ['name', 'description'];

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class, 'doctor_specialty', 'specialty_id', 'doctor_id');
    }
}
