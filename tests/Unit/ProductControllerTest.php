<?php

namespace Tests\Unit\API;

use Tests\TestCase;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Http\Controllers\API\ProductController;
use App\Http\Requests\ProductRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $productRepository;
    protected $productController;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = $this->app->make(ProductRepository::class);
        $this->productController = new ProductController($this->productRepository);
    }

    /** @test */
    public function it_can_get_all_products()
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->productController->index();

        $this->assertEquals($products->toArray(), $response->toArray());
    }

}
