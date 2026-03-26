<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [

            // 🔪 НОЖІ
            'knife' => [
                'Сталь' => ['D2', 'AUS-8', '440C', 'N690', 'Elmax'],
                'Тип ножа' => ['Складний', 'Фіксований'],
                'Твердість (HRC)' => ['58', '59', '60', '61'],
                'Покриття' => ['Satin', 'Stonewash', 'Black coating'],
            ],

            // 🧱 МАТЕРІАЛИ
            'material' => [
                'Матеріал' => ['Шкіра', 'Дерево', 'Пластик', 'Мікарта', 'G10'],
                'Колір' => ['Чорний', 'Коричневий', 'Оливковий', 'Пісочний'],
                'Текстура' => ['Гладка', 'Шорстка'],
                'Походження' => ['Україна', 'Європа', 'США'],
            ],

            // 📦 ЗАМОВЛЕННЯ
            'order' => [
                'Сталь' => ['D2', 'AUS-8', '440C', 'N690', 'Elmax'],
                'Тип ножа' => ['Складний', 'Фіксований'],
                'Твердість (HRC)' => ['58', '59', '60', '61'],
                'Покриття' => ['Satin', 'Stonewash', 'Black coating'],
            ],
        ];

        foreach ($attributes as $group => $items) {
            foreach ($items as $attrName => $values) {

                $attribute = Attribute::firstOrCreate(
                    ['slug' => Str::slug($attrName)],
                    [
                        'name' => $attrName,
                        'group' => $group,
                        'description' => null,
                        'sort' => 0,
                    ]
                );

                foreach ($values as $value) {
                    AttributeValue::firstOrCreate([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                }
            }
        }

        // 🔥 Опціонально: прив’язка до продуктів
        $this->attachAttributesToProducts();
    }

    private function attachAttributesToProducts(): void
    {
        $products = Product::all();

        foreach ($products as $product) {

            // якщо є поле category або type — краще фільтрувати
            $group = $product->type ?? 'knife';

            $attributes = Attribute::where('group', $group)->get();

            foreach ($attributes as $attribute) {

                $value = $attribute->values()->inRandomOrder()->first();

                if (! $value) {
                    continue;
                }

                ProductAttributeValue::firstOrCreate([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'attribute_value_id' => $value->id,
                ]);
            }
        }
    }
}
