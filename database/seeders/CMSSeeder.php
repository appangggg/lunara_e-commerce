<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;
use App\Models\Drop;
use App\Models\Setting;

class CMSSeeder extends Seeder
{
    public function run()
    {
        // Settings
        Setting::create(['key' => 'manifesto_title', 'value' => 'UNCOMPROMISING UTILITY']);
        Setting::create(['key' => 'manifesto_desc', 'value' => 'Every piece is engineered with precision. We source military-grade textiles and combine them with brutalist silhouettes for a uniform built for the modern urban landscape.']);

        // Collections
        Collection::create([
            'name' => 'Obsidian Core',
            'slug' => 'obsidian-core',
            'label' => 'Active Series',
            'description' => 'Matte black finishes, highly water-resistant shell fabrics, and stealth-oriented designs.',
            'image_url' => 'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?auto=format&fit=crop&q=80',
            'color_theme' => 'primary'
        ]);
        
        Collection::create([
            'name' => 'Neon Synthesis',
            'slug' => 'neon-synthesis',
            'label' => 'Limited Run',
            'description' => 'High-visibility accents paired with ultra-lightweight structural meshes.',
            'image_url' => 'https://images.unsplash.com/photo-1525208643878-124b815e1da7?auto=format&fit=crop&q=80',
            'color_theme' => 'error'
        ]);

        // Drops
        Drop::create([
            'name' => 'Apex Tactical Sling',
            'slug' => 'apex-tactical-sling',
            'label' => 'LIVE NOW: PROTOCOL 001',
            'description' => 'Crafted from military-grade ballistic nylon. Features magnetic fidlock buckles and a modular compartment system. Only 50 units produced worldwide.',
            'status' => 'live',
            'date_label' => 'NOW',
            'price_label' => 'Rp 1.250.000',
            'units_left' => 12,
            'image_url' => 'https://images.unsplash.com/photo-1550503195-2ab13e2f9cc4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
        ]);

        Drop::create([
            'name' => 'AeroShell Parka V2',
            'slug' => 'aeroshell-parka-v2',
            'label' => 'UPCOMING',
            'description' => 'Lightweight thermoregulating armor.',
            'status' => 'upcoming',
            'date_label' => '28.06',
            'price_label' => 'Rp 3.500.000',
            'units_left' => 0
        ]);

        Drop::create([
            'name' => 'Zero-G Cargos',
            'slug' => 'zero-g-cargos',
            'label' => 'CLASSIFIED',
            'description' => 'Experimental anti-gravity weave. Classified drop.',
            'status' => 'upcoming',
            'date_label' => '15.07',
            'price_label' => 'TBA',
            'units_left' => 0
        ]);
    }
}
