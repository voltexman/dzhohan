<?php

use App\Enums\DeliveryMethod;
use App\Enums\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // Номер замовлення (напр. #1001)
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->enum('delivery_method', DeliveryMethod::values());
            $table->string('city');
            $table->string('address');
            $table->text('comment')->nullable();
            $table->decimal('total_price', 12, 2);
            $table->enum('status', OrderStatus::values())->default(OrderStatus::Pending);
            $table->timestamps();
        });

        // Таблиця товарів у замовленні
        Schema::create('order_items', function (Blueprint $table) {
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
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
