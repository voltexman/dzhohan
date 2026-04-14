<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_manufactures', function (Blueprint $table) {
            $table->renameColumn('sheath_type', 'sheath');

            $table->boolean('sheath')
                ->default(false)
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_manufactures', function (Blueprint $table) {
            $table->string('sheath_type')
                ->nullable()
                ->change();

            $table->renameColumn('sheath', 'sheath_type');
        });
    }
};
