<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLedger extends Model
{
    use HasFactory;

    protected $table = 'inventory_ledger';

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'balance_after',
        'reference_type',
        'reference_id',
        'note',
        'user_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'balance_after' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            'sale' => 'Sale',
            'purchase' => 'Purchase',
            'opening' => 'Opening Stock',
            'adjustment' => 'Adjustment',
            'return' => 'Return / Refund',
            default => ucfirst($this->type),
        };
    }
}
