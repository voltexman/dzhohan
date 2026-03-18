<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['phone', 'email', 'socials', 'contact', 'location', 'address', 'online', 'maintenance_until'];

    protected $casts = [
        'faqs' => 'array',
        'socials' => 'array',
        'online' => 'boolean',
        'maintenance_until' => 'datetime',
    ];
}
