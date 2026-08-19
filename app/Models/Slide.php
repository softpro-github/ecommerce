<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'type', 'image_path', 'heading', 'cta_text', 'cta_link', 'sort_order', 'enabled',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
