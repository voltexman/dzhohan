<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // morph
            $table->morphs('commentable');

            // гість
            $table->string('author_name');
            $table->string('ip_address', 45)->nullable();

            // вкладені відповіді
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();

            $table->text('body');

            $table->unsignedTinyInteger('rating')->nullable(); // 1-5

            $table->boolean('is_approved')->default(true);

            $table->index('commentable_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
