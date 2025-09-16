<?php
// create_order_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration {
    public function up(){
        Schema::create('order_items', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->decimal('quantity',10,2)->default(0);
            $table->decimal('unit_cost',10,2)->nullable();
            $table->decimal('subtotal',10,2)->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
        });
    }
    public function down(){ Schema::dropIfExists('order_items'); }
}
