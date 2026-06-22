<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name1 = 'Obsidian Tech Denim';
        Product::create([
            'name' => $name1,
            'slug' => Str::slug($name1),
            'description' => 'Engineered for the urban environment. Features water-resistant coating, articulated knees, and multiple hidden compartments for your EDC.',
            'price' => 245000,
            'stock' => 50,
            'image_url' => 'https://images.unsplash.com/photo-1542272454315-4c01d7abdf4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        ]);
        
        $name2 = 'Neon Grid Windbreaker';
        Product::create([
            'name' => $name2,
            'slug' => Str::slug($name2),
            'description' => 'Lightweight, reflective windbreaker designed for nighttime visibility and maximum aesthetic impact.',
            'price' => 320000,
            'stock' => 30,
            'image_url' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        ]);
    }
}
