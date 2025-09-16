<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration {
    public function up(){
        Schema::create('ingredients', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('category')->nullable(); // perecedero/no perecedero
            $table->date('expiration_date')->nullable();
            $table->decimal('stock',10,2)->default(0);
            $table->decimal('min_stock',10,2)->default(0);
            $table->string('unit')->default('pcs'); // kg, liters, pcs
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->decimal('cost',10,2)->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }
    public function down(){ Schema::dropIfExists('ingredients'); }
}
