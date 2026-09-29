<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        
        // Settings are loaded in the product index, so let's mock one if needed
        // Or simply create a generic setting if it assumes Settings table exists.
    }

    public function test_product_listing_page_displays_products()
    {
        // Arrange: Create some products
        $product1 = Product::factory()->create(['name' => 'Test Product A']);
        $product2 = Product::factory()->create(['name' => 'Test Product B']);

        // Act: Visit the index page
        $response = $this->get('/');

        // Assert: Ensure products are visible
        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertSee('Test Product A');
        $response->assertSee('Test Product B');
    }

    public function test_search_functionality_filters_products_correctly()
    {
        Product::factory()->create(['name' => 'Laravel T-Shirt']);
        Product::factory()->create(['name' => 'VueJS T-Shirt']);

        $response = $this->get('/?search=Laravel');

        $response->assertStatus(200);
        $response->assertSee('Laravel T-Shirt');
        $response->assertDontSee('VueJS T-Shirt');
    }

    public function test_product_details_page_is_accessible_for_existing_product()
    {
        $product = Product::factory()->create(['name' => 'Awesome Product', 'description' => 'Great product details']);

        $response = $this->get('/products/' . $product->id);

        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        $response->assertSee('Awesome Product');
        $response->assertSee('Great product details');
    }

    public function test_product_details_page_returns_404_for_non_existing_product()
    {
        $response = $this->get('/products/9999');

        $response->assertStatus(404);
    }
}
