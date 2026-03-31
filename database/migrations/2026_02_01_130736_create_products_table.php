<?php

use App\Enums\CurrencyType;
use App\Enums\KnifeCollection;
use App\Enums\ProductCategory;
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
            $table->string('sku', 50)->nullable()->unique();
            $table->text('description')->nullable();

            $table->decimal('price', 8, 2);
            $table->enum('currency', CurrencyType::cases())->default(CurrencyType::UAH);
            $table->unsignedInteger('quantity')->default(1);
            $table->boolean('is_active')->default(true);
            $table->string('youtube_video_id')->nullable();
            $table->json('additional_attributes')->nullable();

            $table->enum('category', ProductCategory::values())->index();
            $table->enum('collection', KnifeCollection::values())->nullable()->index();

            $table->decimal('total_length', 6, 1)->default(0)->nullable()->comment('Загальна довжина, мм');
            $table->decimal('blade_length', 6, 1)->default(0)->nullable()->comment('Довжина леза, мм');
            $table->decimal('blade_thickness', 4, 1)->default(0)->nullable()->comment('Товщина леза, мм');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
