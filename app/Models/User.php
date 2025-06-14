<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $primaryKey = 'User_ID';

    protected $fillable = ['Name', 'Email', 'Password', 'Role_ID'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'Role_ID');
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'Doctor_ID');
    }
}
