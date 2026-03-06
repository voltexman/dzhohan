<?php

use App\Enums\Order\DeliveryMethod;
use App\Enums\Order\OrderStatus;
use App\Enums\Order\OrderType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->enum('delivery_method', DeliveryMethod::values());
            $table->string('city');
            $table->string('address');
            $table->text('comment')->nullable();
            $table->decimal('total_price', 12, 2);
            $table->enum('type', OrderType::values())->default(OrderType::Purchase->value);
            $table->enum('status', OrderStatus::values())->default(OrderStatus::Pending);
            $table->json('custom_options')->nullable()->comment('Якщо замовлення на виготовлення');
            $table->timestamps();
        });

        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('product_name');
            $table->json('custom_options')->nullable();
            $table->integer('qty');
            $table->decimal('price', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_products');
        Schema::dropIfExists('orders');
    }
};
