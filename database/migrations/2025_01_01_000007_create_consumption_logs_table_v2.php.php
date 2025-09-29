<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumptionLogsTablev2 extends Migration {
    public function up(){
        Schema::create('consumption_logs', function(Blueprint $table){
            $table->id();
            $table->date('date'); // día del consumo
            $table->unsignedBigInteger('dish_id')->nullable(); // platillo opcional
            $table->unsignedBigInteger('ingredient_id')->nullable(); // ingrediente opcional
            $table->decimal('quantity',10,2)->default(1); // unidades consumidas
            $table->timestamps();

            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('set null');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('set null');
        });
    }
    public function down(){ Schema::dropIfExists('consumption_logs'); }
}
