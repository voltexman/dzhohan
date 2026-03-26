<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value_id',
        'sort',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function value()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
