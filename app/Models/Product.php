<?php

namespace App\Models;

use App\Enums\CurrencyType;
use App\Enums\KnifeCollection;
use App\Enums\ProductCategory;
use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Likeable, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'quantity',
        'is_active',
        'category',
        'collection',
        'currency',

        'total_length',    // Загальна довжина
        'blade_length',    // Довжина леза
        'blade_thickness', // Товщина леза
    ];

    protected $casts = [
        // Базові фільтри
        'category' => ProductCategory::class,
        'collection' => KnifeCollection::class,
        'currency' => CurrencyType::class,

        // Числові значення (для точності)
        'total_length' => 'decimal:2',
        'blade_length' => 'decimal:2',
        'blade_thickness' => 'decimal:2',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getStockAttribute()
    {
        return $this->quantity;
    }

    public function isSold(): bool
    {
        return $this->quantity === 0;
    }

    public function hasStock(): bool
    {
        return $this->quantity > 0;
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->where('is_active', true)

            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($filters['collections'] ?? null, fn($q, $v) => $q->whereIn('collection', (array) $v))
            ->when(($filters['status'] ?? null) === 'in_stock', fn($q) => $q->where('quantity', '>', 0))
            ->when(($filters['status'] ?? null) === 'sold', fn($q) => $q->where('quantity', 0))
            ->when($filters['price_from'] ?? null, fn($q, $v) => $q->where('price', '>=', $v))
            ->when($filters['price_to'] ?? null, fn($q, $v) => $q->where('price', '<=', $v))
            ->when($filters['blade_length_from'] ?? null, fn($q, $v) => $q->where('blade_length', '>=', $v))
            ->when($filters['blade_length_to'] ?? null, fn($q, $v) => $q->where('blade_length', '<=', $v))
            ->when($filters['blade_thickness_from'] ?? null, fn($q, $v) => $q->where('blade_thickness', '>=', $v))
            ->when($filters['blade_thickness_to'] ?? null, fn($q, $v) => $q->where('blade_thickness', '<=', $v))

            // 🔥 ДИНАМІЧНІ АТРИБУТИ
            ->when($filters['attributes'] ?? null, function ($q, $attributes) {
                foreach ($attributes as $slug => $values) {

                    if (empty($values)) {
                        continue;
                    }

                    $q->whereHas('attributeValues', function ($q2) use ($slug, $values) {
                        $q2->whereIn('attribute_values.id', $values)
                            ->whereHas('attribute', fn($q3) => $q3->where('slug', $slug));
                    });
                }
            });
    }

    public function attributeValues()
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_attribute_values',
            'product_id',
            'attribute_value_id'
        )->withPivot('attribute_id', 'sort');
    }

    public function knifeAttributes()
    {
        return $this->hasMany(ProductAttributeValue::class)
            ->whereHas('attribute', fn($q) => $q->where('group', 'knife'))
            ->orderBy('sort');
    }

    // public function url(): string
    // {
    //     if ($this->category === ProductCategory::KNIFE) {

    //         if (!$this->collection) {
    //             throw new \Exception("Knife [{$this->id}] must have collection");
    //         }

    //         return route('knife.show', [
    //             'collection' => $this->collection->value,
    //             'knife' => $this->slug,
    //         ]);
    //     }

    //     return route('material.show', [
    //         'product' => $this->slug,
    //     ]);
    // }

    public function productAttributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class)->orderBy('sort');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->withResponsiveImages()
            ->fit(Fit::Crop);
    }
}
