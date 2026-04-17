<?php

namespace App\Models\AbandonedCart;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'ac_cart_items';

    protected $fillable = [
        'ac_cart_id',
        'product_id',
        'quantity',
        'calculated_price',
        'meta',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'calculated_price' => 'decimal:2',
        'meta' => 'array',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'ac_cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
