<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $primaryKey = 'Report_ID';

    protected $fillable = ['Patients_ID', 'file_path'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'Patients_ID');
    }
}
