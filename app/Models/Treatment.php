<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    protected $primaryKey = 'Treatment_ID';

    protected $fillable = ['Diagnosis_ID', 'Treatment_Name', 'description'];

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(Diagnosis::class, 'Diagnosis_ID');
    }
}
