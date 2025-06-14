<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diagnosis extends Model
{
    protected $primaryKey = 'Diagnosis_ID';

    protected $fillable = ['Record_ID', 'disease_stage', 'confidence'];

    public function record(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class, 'Record_ID');
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'Diagnosis_ID');
    }
}

