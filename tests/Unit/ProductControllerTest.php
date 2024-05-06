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

    /** @test */
    public function it_can_store_a_product()
    {
        // Create a mock instance of ProductRequest
        $request = Mockery::mock(ProductRequest::class);

        // Define the expected validated data
        $validatedData = [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'price' => 10,
            'is_show' => true,
            'category' => 'Test Category',
        ];

        // Mock the validated method to return the expected data
        $request->shouldReceive('validated')->once()->andReturn($validatedData);

        // Act: Call the store method on the controller with the mocked ProductRequest object
        $response = $this->productController->store($request);

        // Assert: Check if the response status is 201 (created)
        $this->assertEquals(201, $response->status());
    }

    /** @test */
    public function it_can_get_a_product_by_id()
    {
        // Create a product
        $product = Product::factory()->create();

        // Call the show method on the controller to get the product by its ID
        $response = $this->productController->show($product->id);

        // Assert: Check if the response status is 200 (OK)
        $this->assertEquals(200, $response->getStatusCode());

        // Assert: Check if the response JSON contains the expected product data
        $this->assertEquals($product->toArray(), $response->getData(true));
    }

    /** @test */
    public function it_can_update_a_product()
    {
        // Create a product
        $product = Product::factory()->create();

        // Define the updated data
        $updatedData = [
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
            'price' => 20,
            'is_show' => true,
            'category' => 'Updated Category',
        ];

        // Create a mock instance of ProductRequest
        $request = Mockery::mock(ProductRequest::class);

        // Mock the validated method to return the expected data
        $request->shouldReceive('validated')->once()->andReturn($updatedData);

        // Call the update method on the controller to update the product
        $response = $this->productController->update($request, $product->id);

        // Assert: Check if the response status is 200 (OK)
        $this->assertEquals(200, $response->getStatusCode());
    }

    // /** @test */
    public function it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $this->productController->destroy($product->id);

        $this->assertDeleted($product);
    }
}
