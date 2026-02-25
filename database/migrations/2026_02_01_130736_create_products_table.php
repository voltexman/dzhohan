<?php

use App\Enums\BladeFinish;
use App\Enums\BladeGrind;
use App\Enums\BladeShape;
use App\Enums\HandleMaterial;
use App\Enums\ProductCategory;
use App\Enums\SheathType;
use App\Enums\SteelType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->longText('description')->nullable();

            $table->decimal('price', 10, 2);
            $table->unsignedInteger('quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->enum('category', ProductCategory::values())->index();

            $table->decimal('total_length', 8, 2)->nullable()->comment('Загальна довжина, мм');
            $table->decimal('blade_length', 8, 2)->nullable()->comment('Довжина леза, мм');
            $table->decimal('blade_thickness', 5, 2)->nullable()->comment('Товщина леза, мм');

            $table->enum('steel', SteelType::values())->nullable()->index()->comment('Марка сталі клинка');
            $table->enum('blade_shape', BladeShape::values())->nullable()->index()->comment('Геометрія клинка');
            $table->enum('blade_finish', BladeFinish::values())->nullable()->comment('Візуальна обробка поверхні');
            $table->enum('blade_grind', BladeGrind::values())->nullable()->comment('Тип спусків леза');
            $table->enum('handle_material', HandleMaterial::values())->nullable()->comment('Матеріал руків’я');
            $table->enum('sheath', SheathType::values())->nullable()->comment('Матеріал та тип піхов/чохла');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
