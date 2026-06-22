<?php

// Coded by: Muh. Asyfar Arifin Liwan (NIM: 60200124013)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image_url',
        'is_limited',
        'category',
    ];

    /**
     * Returns size options based on product category.
     * clothing => S, M, L, XL
     * shoes    => 32, 33, ... 44
     * other    => [] (no size)
     */
    public function getSizes(): array
    {
        return match($this->category) {
            'clothing' => ['S', 'M', 'L', 'XL'],
            'shoes'    => ['32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44'],
            default    => [],
        };
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
