<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function create($data)
    {
        return Product::create($data);
    }

    public function update(Product $product, $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        $product->delete();
    }

    public function getById($id)
    {
        return Product::findOrFail($id);
    }

    public function getAll()
    {
        return Product::all();
    }
}
