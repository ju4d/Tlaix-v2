<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model {
    protected $fillable = ['name','category','expiration_date','stock','min_stock','unit','supplier_id','cost'];
    public function supplier(){ return $this->belongsTo(Supplier::class); }
    public function dishes(){ return $this->belongsToMany(Dish::class,'dish_ingredient')->withPivot('quantity_required'); }
}
