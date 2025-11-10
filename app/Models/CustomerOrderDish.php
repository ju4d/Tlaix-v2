<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderDish extends Model
{
    protected $fillable = ['customer_order_id', 'dish_id', 'quantity', 'completed', 'received'];

    public function customerOrder()
    {
        return $this->belongsTo(CustomerOrder::class);
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
