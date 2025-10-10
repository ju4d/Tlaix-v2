<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->enum('reason', [
                'expired',
                'damaged_in_storage',
                'customer_return',
                'inventory_error',
                'theft_loss',
                'internal_use',
                'other'
            ]);
            $table->text('comments')->nullable();
            $table->decimal('unit_cost_at_time', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_records');
    }
};
