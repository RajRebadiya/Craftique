<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showcase extends Model
{
    protected $table = 'showcases';

    protected $fillable = [
        'seller_id',
        'type',

        // legacy
        'title',
        'subtitle',
        'intro',
        'description',

        // bilingual
        'title_gr',
        'title_en',
        'subtitle_gr',
        'subtitle_en',
        'intro_gr',
        'intro_en',
        'description_gr',
        'description_en',
        'hashtags',

        'cover_image',
        'main_visual',
        'collection_items_json',
        'linked_products',
        'billing_period',
        'status',
    ];

    protected $casts = [
        'collection_items_json' => 'array',
    ];
}
