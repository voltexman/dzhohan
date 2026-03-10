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
            $table->enum('type', OrderType::values())->default(OrderType::Purchase->value);
            $table->enum('status', OrderStatus::values())->default(OrderStatus::Pending);
            $table->timestamps();
        });

        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');

            $table->string('name');
            $table->integer('qty');
            $table->decimal('price', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('order_manufactures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->string('knife_type')->nullable();

            // клинок
            $table->string('blade_shape')->nullable();
            $table->string('blade_steel')->nullable();
            $table->string('blade_grind')->nullable();
            $table->string('blade_finish')->nullable();
            $table->integer('blade_length')->nullable();
            $table->integer('blade_thickness')->nullable();

            // руків’я
            $table->string('handle_material')->nullable();
            $table->string('handle_color')->nullable();

            // піхви
            $table->string('sheath_type')->nullable();
            $table->string('sheath_carry')->nullable();

            // гравіювання
            $table->boolean('engraving')->default(false);
            $table->string('engraving_text')->nullable();

            // додатково
            $table->text('notes')->nullable();

            $table->integer('qty')->nullable()->default(1);
            $table->decimal('price', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_products');
        Schema::dropIfExists('order_manufactures');
        Schema::dropIfExists('orders');
    }
};
