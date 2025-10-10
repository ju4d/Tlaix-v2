<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraints extends Migration
{
    public function up()
    {
        // Proveedores: nombre y email únicos
        Schema::table('suppliers', function (Blueprint $table) {
            $table->unique('name');
            $table->unique('email');
        });

        // Ingredientes: nombre único por proveedor
        Schema::table('ingredients', function (Blueprint $table) {
            $table->unique(['name', 'supplier_id']);
        });

        // Platillos: nombre único
        Schema::table('dishes', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->dropUnique(['email']);
        });

        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropUnique(['name', 'supplier_id']);
        });

        Schema::table('dishes', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
}