<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Attribute extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'description', 'group', 'sort'];

    protected static function booted()
    {
        static::saving(function (Attribute $attribute) {
            if (empty($attribute->slug) || $attribute->isDirty('name')) {
                $attribute->slug = Str::slug($attribute->name);
            }
        });
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
