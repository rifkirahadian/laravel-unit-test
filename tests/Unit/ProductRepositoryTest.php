<?php
use Tests\TestCase;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductRepositoryTest extends TestCase
{
    /** @var ProductRepository */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialize the repository
        $this->repository = new ProductRepository();
    }

    /** @test */
    public function it_can_create_a_product()
    {
        // Create new product data
        $data = [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'price' => 10,
            'is_show' => true,
            'category' => 'Test Category',
        ];

        // Call the create method on the repository
        $product = $this->repository->create($data);

        // Assert: Check that the product was created successfully
        $this->assertInstanceOf(Product::class, $product);
        $this->assertDatabaseHas('products', $data);
    }

    /** @test */
    public function it_can_update_a_product()
    {
        // Create a product
        $product = Product::factory()->create();

        // Update data
        $updatedData = [
            'name' => 'Updated Product',
            'price' => 20,
        ];

        // Call the update method on the repository
        $updatedProduct = $this->repository->update($product, $updatedData);

        // Assert: Check that the product was updated successfully
        $this->assertEquals($updatedData['name'], $updatedProduct->name);
        $this->assertEquals($updatedData['price'], $updatedProduct->price);
    }

}