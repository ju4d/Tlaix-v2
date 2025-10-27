<?php
// Reemplaza el contenido de app/Models/OrderItem.php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    public function dish() {
        return $this->belongsTo(Dish::class);
    }
    protected $fillable = ['order_id','ingredient_id','quantity','unit_cost','subtotal'];

    // Deshabilitar timestamps ya que la tabla no los tiene
    public $timestamps = false;

    public function ingredient(){
        return $this->belongsTo(Ingredient::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
