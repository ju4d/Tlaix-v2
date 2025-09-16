<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishesTable extends Migration {
    public function up(){
        Schema::create('dishes', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price',10,2)->default(0);
            $table->boolean('available')->default(true);
            $table->timestamps();
        });
        Schema::create('dish_ingredient', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('dish_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->decimal('quantity_required',10,2)->default(1);
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
        });
    }
    public function down(){
        Schema::dropIfExists('dish_ingredient');
        Schema::dropIfExists('dishes');
    }
}
