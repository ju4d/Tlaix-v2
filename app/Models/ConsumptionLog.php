<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ConsumptionLog extends Model {
    protected $fillable = ['date','dish_id','ingredient_id','quantity'];
    public function dish(){ return $this->belongsTo(Dish::class); }
    public function ingredient(){ return $this->belongsTo(Ingredient::class); }
}
