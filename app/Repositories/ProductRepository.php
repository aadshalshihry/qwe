<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::all();
    }

    public function get($where = [], $with = [])
    {
        $query = Product::query();
        if (!empty($where)) {
            $query = $query->where($where);
        }

        if (!empty($with)) {
            $query = $query->with($with);
        }

        return $query->get();
    }

    public function find($id)
    {
        return Product::find($id);
    }

    public function first($where = [], $with = [])
    {
        $query = Product::query();
        if (!empty($where)) {
            $query = $query->where($where);
        }

        if (!empty($with)) {
            $query = $query->with($with);
        }

        return $query->first();
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

    public function firstOrCreate($id, array $attributes)
    {
        if ($variation = Product::find($id)) {
            return $variation;
        }

        $variation = Product::create($attributes);
        return $variation;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return true;
    }
}
