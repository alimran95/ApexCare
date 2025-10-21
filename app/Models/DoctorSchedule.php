<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DoctorSchedule extends Model
{
    protected $primaryKey = 'schedule_id';
    protected $fillable = ['doctor_id', 'day_of_week', 'start_time', 'end_time'];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'schedule_id', 'schedule_id');
    }
}
