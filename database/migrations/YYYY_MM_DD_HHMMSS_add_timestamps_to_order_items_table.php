<?php
// Crear esta migración con: php artisan make:migration add_timestamps_to_order_items_table
// Archivo: database/migrations/YYYY_MM_DD_HHMMSS_add_timestamps_to_order_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
}
