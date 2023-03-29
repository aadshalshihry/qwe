<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::all();
    }

    public function find($id)
    {
        return Product::find($id);
    }

    public function create(array $attributes)
    {
        return Product::create($attributes);
    }

    public function update($id, array $attributes)
    {
        $product = Product::findOrFail($id);

        $product->update($attributes);

        return $product;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return true;
    }
}
