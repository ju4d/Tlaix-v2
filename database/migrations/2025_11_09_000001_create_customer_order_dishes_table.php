<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('customer_order_dishes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_order_id');
            $table->unsignedBigInteger('dish_id');
            $table->integer('quantity')->default(1);
            $table->boolean('completed')->default(false);
            $table->boolean('received')->default(false);
            $table->timestamps();

            $table->foreign('customer_order_id')->references('id')->on('customer_orders')->onDelete('cascade');
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('customer_order_dishes');
    }
};
