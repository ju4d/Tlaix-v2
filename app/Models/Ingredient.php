<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model {
    protected $fillable = ['name','category','expiration_date','stock','min_stock','unit','supplier_id','cost'];
    
    protected $casts = [
        'cost' => 'decimal:2',
        'stock' => 'decimal:2',
        'min_stock' => 'decimal:2'
    ];

    public function supplier(){ return $this->belongsTo(Supplier::class); }
    public function dishes(){ return $this->belongsToMany(Dish::class,'dish_ingredient')->withPivot('quantity_required'); }
}
