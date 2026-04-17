<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use PreventDemoModeChanges;

    use SoftDeletes;

    protected $casts = [
        'product_ids' => 'array',
    ];
    
    public function category() {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

}
