<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact', 'rating', 'text', 'is_selected'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected $casts = [
        'rating' => 'integer',
        'is_selected' => 'boolean',
    ];
}
