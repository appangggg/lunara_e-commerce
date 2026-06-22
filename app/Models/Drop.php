<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'label', 'description', 'image_url', 'status', 'date_label', 'price_label', 'units_left', 'is_featured'
    ];
}
