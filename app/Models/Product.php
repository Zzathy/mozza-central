<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'cost_price',
        'price',
        'price_note',
        'min_stock',
        'is_active',
    ];

    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected function totalStock(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->productBatches()->sum('remaining_qty')
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productBatches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }
}
