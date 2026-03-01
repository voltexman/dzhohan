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
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // morph
            $table->morphs('commentable');
            $table->index('commentable_id');

            // гість
            $table->string('author_name')->nullable();
            $table->string('ip_address', 45)->nullable();

            // вкладені відповіді
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();

            $table->text('body');

            $table->unsignedTinyInteger('rating')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
