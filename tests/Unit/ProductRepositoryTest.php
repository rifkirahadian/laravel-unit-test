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
}