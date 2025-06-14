<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $primaryKey = 'Patients_ID';

    protected $fillable = [
        'Name',
        'Date_Of_Birth',
        'Contact_Info',
        'Doctor_ID',
        'User_ID',
    ];


    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'Doctor_ID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_ID');
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'Patients_ID');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'Patients_ID');
    }
}
