<?php

namespace App\Services;

use App\Models\Product;
class ProductService
{
    public function getAllProducts()
    {
        
        return Product::all();
    }

    public function getProductById($id)
    {
        return Product::findOrFail($id);
    }

    public function createProduct($data)
    {
        return Product::create($data);
    }

    public function updateProduct($id, $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return $product;
    }
}