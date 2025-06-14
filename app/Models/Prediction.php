<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    protected $primaryKey = 'Prediction_ID';

    protected $fillable = ['Record_ID', 'result', 'probabilities', 'notes'];

    protected $casts = [
        'probabilities' => 'array',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class, 'Record_ID');
    }
}

