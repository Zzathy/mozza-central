<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductBatch extends Model
{
    protected $fillable = [
        'stock_entry_id',
        'product_id',
        'initial_qty',
        'remaining_qty',
        'buy_price',
    ];

    /** @use HasFactory<\Database\Factories\ProductBatchFactory> */
    use HasFactory;

    public function stockEntry(): BelongsTo
    {
        return $this->belongsTo(StockEntry::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
