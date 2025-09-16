<?php
// OrderItem.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    protected $fillable = ['order_id','ingredient_id','quantity','unit_cost','subtotal'];
    public function ingredient(){ return $this->belongsTo(Ingredient::class); }
}
