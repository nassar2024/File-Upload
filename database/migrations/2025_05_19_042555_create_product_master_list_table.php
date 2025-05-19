<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_master_list', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('type');
            $table->string('brand');
            $table->string('model');
            $table->string('capacity');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_master_list');
    }
};