<?php

namespace App\Models\AbandonedCart;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'ac_carts';

    protected $fillable = [
        'user_id',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class, 'ac_cart_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function abandonedCart()
    {
        return $this->hasOne(AbandonedCart::class, 'ac_cart_id');
    }
}
