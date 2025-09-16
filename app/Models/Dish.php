<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model {
    protected $fillable = ['name','description','price','available'];
    public function ingredients(){ return $this->belongsToMany(Ingredient::class,'dish_ingredient')->withPivot('quantity_required'); }
}
