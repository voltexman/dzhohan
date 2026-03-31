<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');

            $table->text('description')->nullable();
            $table->string('group')->nullable();
            $table->integer('sort')->default(0);

            $table->unique(['group', 'name']);
            $table->unique(['group', 'slug']);
        });

        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            $table->string('value');
            $table->unique(['attribute_id', 'value']);
        });

        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();

            $table->foreignId('attribute_value_id')->constrained()->cascadeOnDelete();

            $table->unique(['product_id', 'attribute_id', 'attribute_value_id'], 'pav_unique');
            $table->integer('sort')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
    }
};
