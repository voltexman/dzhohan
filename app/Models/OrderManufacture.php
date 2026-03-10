<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderManufacture extends Model
{
    protected $fillable = [
        'knife_type',

        'blade_shape',
        'blade_steel',
        'blade_grind',
        'blade_finish',
        'blade_length',
        'blade_thickness',

        'handle_material',
        'handle_color',

        'sheath_type',
        'sheath_carry',

        'engraving',
        'engraving_text',

        'notes',
    ];

    protected $casts = [
        'blade_length' => 'integer',
        'blade_thickness' => 'integer',

        'engraving' => 'boolean',
        'subscribe' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
