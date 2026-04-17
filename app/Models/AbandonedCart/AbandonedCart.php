<?php

namespace App\Models\AbandonedCart;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AbandonedCart extends Model
{
    use HasFactory;

    protected $table = 'ac_abandoned_carts';

    public const STATUS_PENDING = 'pending';
    public const STATUS_DISPATCHED = 'dispatched';
    public const STATUS_PROGRESS = 'progress';
    public const STATUS_RECEIVED = 'received';
    public const STATUS_BACK_TO_CART = 'back_to_cart';
    public const STATUS_RECOVERED = 'recovered';
    public const STATUS_LOST = 'lost';


    protected $fillable = [
        'user_id',
        'ac_cart_id',
        'email',
        'status',
        'is_unsubscribed',
    ];

    protected $casts = [
        'is_unsubscribed' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (AbandonedCart $abandonedCart) {
            $abandonedCart->uuid = (string) Str::orderedUuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'ac_cart_id');
    }
}
