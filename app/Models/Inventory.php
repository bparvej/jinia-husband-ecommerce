<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'quantity',
        'low_stock_threshold',
        'warehouse_location',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Atomically adjust stock for a product and record a matching ledger entry.
     * Positive $delta increases stock (credit), negative decreases it (debit).
     */
    public static function adjustStock(
        $productId,
        $delta,
        $type,
        $note = null,
        $referenceType = null,
        $referenceId = null
    ): InventoryLedger {
        return DB::transaction(function () use ($productId, $delta, $type, $note, $referenceType, $referenceId) {
            $inventory = static::where('product_id', $productId)->lockForUpdate()->first();

            if (!$inventory) {
                $inventory = static::create([
                    'product_id' => $productId,
                    'quantity' => 0,
                    'low_stock_threshold' => 10,
                    'warehouse_location' => 'Dhaka Main',
                ]);
            }

            $newQuantity = max(0, $inventory->quantity + intval($delta));
            $inventory->quantity = $newQuantity;
            $inventory->save();

            return InventoryLedger::create([
                'product_id' => $productId,
                'type' => $type,
                'quantity' => intval($delta),
                'balance_after' => $newQuantity,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'note' => $note,
                'user_id' => auth()->id(),
            ]);
        });
    }
}
