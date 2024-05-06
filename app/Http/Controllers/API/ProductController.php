<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Repositories\ProductRepository;
use Exception;

/**
 * @OA\Info(
 *      title="LARAVEL CRUD",
 *      version="1.0.0",
 *      description="Laravel CRUD with unit test"
 * )
 *
 * @OA\Schema(
 *      schema="ProductRequest",
 *      type="object",
 *      required={"name", "price", "is_show", "category"},
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          example="Product Name"
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          example="Product Description"
 *      ),
 *      @OA\Property(
 *          property="price",
 *          type="integer",
 *          example=10
 *      ),
 *      @OA\Property(
 *          property="is_show",
 *          type="boolean",
 *          example=true
 *      ),
 *      @OA\Property(
 *          property="category",
 *          type="string",
 *          example="Product Category"
 *      )
 * )
 * 
 * @OA\Schema(
 *      schema="Product",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="price",
 *          type="number",
 *          format="float",
 *      ),
 *      @OA\Property(
 *          property="is_show",
 *          type="boolean",
 *      ),
 *      @OA\Property(
 *          property="category",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="date-time",
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          format="date-time",
 *      )
 * )
 */
class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/products",
     *      tags={"Products"},
     *      @OA\Response(response="200", description="Get All Products")
     * )
     */
    public function index()
    {
        return $this->productRepository->getAll();
    }

    /**
     * @OA\Post(
     *      path="/api/products",
     *      operationId="storeProduct",
     *      tags={"Products"},
     *      summary="Create a new product",
     *      description="Create a new product with the provided data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Product created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}})
     *          )
     *      )
     * )
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $product = $this->productRepository->create($data);
        return response()->json($product, 201);
    }

    /**
     * @OA\Get(
     *      path="/api/products/{id}",
     *      operationId="getProductById",
     *      tags={"Products"},
     *      summary="Get a product by ID",
     *      description="Returns a single product based on the provided ID",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the product to retrieve",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Product not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Product not found")
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        try {
            $product = $this->productRepository->getById($id);
        } catch (Exception $e) {
            return response()->json([
                'message'   => 'Product Not Found'
            ], 404);
        }

        return response()->json($product);
    }

    /**
     * @OA\Put(
     *      path="/api/products/{id}",
     *      operationId="updateProduct",
     *      tags={"Products"},
     *      summary="Update a existing product",
     *      description="Update a existing product with the provided data",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the product to retrieve",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}})
     *          )
     *      )
     * )
     */
    public function update(ProductRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $product = $this->productRepository->getById($id);
            $product = $this->productRepository->update($product, $data);
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json([
                'message'   => 'Product Not Found'
            ], 404);
        }
        
    }

    /**
     * @OA\Delete(
     *      path="/api/products/{id}",
     *      operationId="deleteProductById",
     *      tags={"Products"},
     *      summary="Delete a product by ID",
     *      description="Returns a single product based on the provided ID",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the product to retrieve",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Product deleted")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Product not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Product not found")
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        try {
            $product = $this->productRepository->getById($id);
            $this->productRepository->delete($product);
        } catch (Exception $th) {
            return response()->json([
                'message'   => 'Product Not Found'
            ], 404);
        }
        
        return response()->json([
            'message'   => 'Product Deleted'
        ], 204);
    }
}
