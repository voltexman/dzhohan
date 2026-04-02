<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('youtube_video_id', 'short_youtube_video_id');

            $table->string('full_youtube_video_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('short_youtube_video_id', 'youtube_video_id');
            $table->dropColumn('full_youtube_video_id');
        });
    }
};
