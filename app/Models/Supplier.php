<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {
    protected $fillable = ['name','contact','phone','email'];
    public function ingredients(){ return $this->hasMany(Ingredient::class); }
}
