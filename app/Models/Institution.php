<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    protected $fillable = [
        'name',
        'app_name',
        'address',
        'phone',
        'email',
        'logo_path',
        'operating_hours',
        'footer_text',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function serviceTypes(): HasMany
    {
        return $this->hasMany(ServiceType::class);
    }

    public function counters(): HasMany
    {
        return $this->hasMany(Counter::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
