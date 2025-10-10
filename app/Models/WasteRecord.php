<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteRecord extends Model
{
    protected $fillable = [
        'ingredient_id',
        'quantity',
        'reason',
        'comments',
        'unit_cost_at_time'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost_at_time' => 'decimal:2',
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function getTotalCostAttribute(): float
    {
        return $this->quantity * $this->unit_cost_at_time;
    }

    public function getTotalCostWithTaxAttribute(): float
    {
        return $this->total_cost * 1.16; // 16% IVA
    }

    public function getTaxAmountAttribute(): float
    {
        return $this->total_cost * 0.16;
    }
}
