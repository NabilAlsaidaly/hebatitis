<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    protected $primaryKey = 'User_ID';

    protected $fillable = ['Name', 'Email', 'Password', 'Role_ID'];

    protected $hidden = ['Password', 'remember_token'];

    // ✅ تشفير تلقائي لكلمة المرور
    public function setPasswordAttribute($value)
    {
        $this->attributes['Password'] = Hash::make($value);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'Role_ID');
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'Doctor_ID');
    }

    public function getAuthPassword()
    {
        return $this->Password;
    }
}
