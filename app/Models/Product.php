<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity_available' => 'integer',
    ];

    /**
     * Get the transactions for the product.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
