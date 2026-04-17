<?php

namespace App\Models\AbandonedCart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AbandonEmail extends Model
{
    use HasFactory;

    protected $table = 'ac_abandon_emails';

    protected $fillable = [
        'ac_abandoned_cart_id',
        'ac_email_notification_id',
        'sent_at',
        'opened_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (AbandonEmail $abandonEmail) {
            $abandonEmail->uuid = (string) Str::orderedUuid();
        });
    }

    public function scopeNotOpened($query)
    {
        return $query->whereNull('opened_at');
    }

    public function abandonedCart()
    {
        return $this->belongsTo(AbandonedCart::class, 'ac_abandoned_cart_id');
    }
}
