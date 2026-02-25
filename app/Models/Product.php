<?php

namespace App\Models;

use App\Enums\BladeFinish;
use App\Enums\BladeGrind;
use App\Enums\BladeShape;
use App\Enums\HandleMaterial;
use App\Enums\ProductCategory;
use App\Enums\SheathType;
use App\Enums\SteelType;
use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

        'total_length',    // Загальна довжина
        'blade_length',    // Довжина леза
        'blade_thickness', // Товщина леза

        'steel',           // Марка сталі (Enum)
        'blade_shape',     // Профіль клинка (Enum)
        'blade_finish',    // Фінішна обробка (Enum)
        'blade_grind',     // Тип спусків (Enum)
        'handle_material', // Матеріал руків'я (Enum)
        'sheath',          // Піхви (Enum)
    ];

    protected $casts = [
        // Базові фільтри
        'category' => ProductCategory::class,

        // Характеристики леза
        'steel' => SteelType::class,
        'blade_shape' => BladeShape::class,
        'blade_finish' => BladeFinish::class,
        'blade_grind' => BladeGrind::class,

        // Руків'я та аксесуари
        'handle_material' => HandleMaterial::class,
        'sheath' => SheathType::class,

        // Числові значення (для точності)
        'total_length' => 'decimal:2',
        'blade_length' => 'decimal:2',
        'blade_thickness' => 'decimal:2',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function hasStock(): bool
    {
        return $this->quantity > 0;
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['categories'] ?? null, fn ($q, $cat) => $q->whereIn('category', $cat))
            //    Фільтр наявності (виправив 'stock' на 'in_stock' згідно з вашим UI)
            ->when(isset($filters['status']) && $filters['status'] !== 'all', function ($q) use ($filters) {
                return $filters['status'] === 'in_stock'
                    ? $q->where('quantity', '>', 0)
                    : $q->where('quantity', '=', 0);
            })

            ->when($filters['price_from'] ?? null, fn ($q, $from) => $q->where('price', '>=', $from))
            ->when($filters['price_to'] ?? null, fn ($q, $to) => $q->where('price', '<=', $to))

            ->when($filters['steels'] ?? null, fn ($q, $v) => $q->whereIn('steel', $v))
            ->when($filters['blade_shapes'] ?? null, fn ($q, $v) => $q->whereIn('blade_shape', $v))
            ->when($filters['handle_materials'] ?? null, fn ($q, $v) => $q->whereIn('handle_material', $v));
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
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id')
            ->where('is_approved', true);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }
}
