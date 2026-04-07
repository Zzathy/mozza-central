<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockEntry extends Model
{
    protected $fillable = [
        'supplier_name',
        'entry_date',
        'notes',
        'total_amount',
        'discount', 
        'final_amount',
    ];

    /** @use HasFactory<\Database\Factories\StockEntryFactory> */
    use HasFactory;

    public function productbatches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }
}
