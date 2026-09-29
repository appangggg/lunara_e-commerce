<?php

namespace Tests\Unit;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductUnitTest extends TestCase
{
    public function test_getsizes_returns_correct_sizes_for_clothing()
    {
        $product = new Product(['category' => 'clothing']);
        
        $sizes = $product->getSizes();
        
        $this->assertEquals(['S', 'M', 'L', 'XL'], $sizes);
    }

    public function test_getsizes_returns_correct_sizes_for_shoes()
    {
        $product = new Product(['category' => 'shoes']);
        
        $sizes = $product->getSizes();
        
        $this->assertEquals(['32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44'], $sizes);
    }

    public function test_getsizes_returns_empty_array_for_other_categories()
    {
        $product = new Product(['category' => 'accessories']);
        
        $sizes = $product->getSizes();
        
        $this->assertEquals([], $sizes);
    }
}
