<?php
// create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {
    public function up(){
        Schema::create('orders', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->date('date')->nullable();
            $table->enum('status',['pending','received','cancelled','pendiente','completada'])->default('pending');
            $table->decimal('total',10,2)->default(0);
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }
    public function down(){ Schema::dropIfExists('orders'); }
}
