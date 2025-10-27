<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderDish extends Model {
    protected $fillable = ['order_id','dish_id','quantity','completed'];
    public function order() { return $this->belongsTo(Order::class); }
    public function dish() { return $this->belongsTo(Dish::class); }
}
