<?php
// Order.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = ['supplier_id','date','status','total'];
    public function items(){ return $this->hasMany(OrderItem::class); }
    public function supplier(){ return $this->belongsTo(Supplier::class); }
}
