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

    // /** @test */
    // public function it_can_update_a_product()
    // {
    //     $product = Product::factory()->create();
    //     $newData = [
    //         'name' => $this->faker->name,
    //         'description' => $this->faker->sentence,
    //         'price' => $this->faker->randomNumber(2),
    //         'is_show' => $this->faker->boolean,
    //         'category' => $this->faker->word,
    //     ];

    //     $request = new \Illuminate\Http\Request([], $newData);

    //     $response = $this->productController->update($request, $product->id);

    //     $this->assertInstanceOf(Product::class, $response);
    //     $this->assertDatabaseHas('products', $newData);
    // }

    // /** @test */
    // public function it_can_delete_a_product()
    // {
    //     $product = Product::factory()->create();

    //     $this->productController->destroy($product->id);

    //     $this->assertDeleted($product);
    // }
}
