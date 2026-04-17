<?php

namespace App\Models\AbandonedCart;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailNotification extends Model
{
    use HasFactory;

    protected $table = 'ac_email_notifications';

    protected $fillable = [
        'name',
        'minutes_after_trigger',
        'is_active',
        'subject',
        'body',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active users.
     */
    public function scopeIsActive(Builder $query): void
    {
        $query->where('is_active', 1);
    }
}
