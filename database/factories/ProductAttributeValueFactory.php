<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeValueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'attribute_id' => Attribute::factory(),
            'attribute_value_id' => AttributeValue::factory(),
            'sort' => 0,
        ];
    }
}
