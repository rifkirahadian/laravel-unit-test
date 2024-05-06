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

    /** @test */
    /** @test */
    public function it_can_delete_a_product()
    {
        // Create a product
        $product = Product::factory()->create();

        // Call the delete method on the repository
        $this->repository->delete($product);

        // Assert: Check that the product has been soft deleted
        $this->assertNotNull($product->fresh()->deleted_at);
    }

    /** @test */
    public function it_can_get_product_by_id()
    {
        // Create a product
        $product = Product::factory()->create();

        // Call the getById method on the repository
        $retrievedProduct = $this->repository->getById($product->id);

        // Assert: Check that the retrieved product matches the original product
        $this->assertEquals($product->toArray(), $retrievedProduct->toArray());
    }

    /** @test */
    public function it_can_get_all_products()
    {
        // Call the getAll method on the repository
        $retrievedProducts = $this->repository->getAll();

        // Assert: Check that the number of retrieved products matches the number of created products
        $this->assertCount(3, $retrievedProducts);
    }
}