<?php

namespace App\Models;

use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Comment extends Model
{
    use HasFactory, Likeable, HasRecursiveRelationships;

    protected $fillable = ['user_id', 'parent_id', 'commentable_id', 'commentable_type', 'author_name', 'body', 'is_active', 'ip_address'];

    public function scopePopular($query)
    {
        return $query
            ->withCount([
                'likes',
                'descendants as descendants_count'
            ])
            ->orderByDesc('descendants_count')
            ->orderByDesc('likes_count')
            ->orderByDesc('created_at');
    }

    protected static function booted()
    {
        static::creating(function ($comment) {
            $comment->parent_id = $comment->parent_id ?: null;

            $comment->body = strip_tags(trim($comment->body));

            $comment->ip_address = request()->ip();

            if (auth()->check()) {
                $comment->user_id = auth()->id();
                $comment->author_name = auth()->user()->name;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
