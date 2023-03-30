<?php

namespace App\Repositories;

use App\Models\ProductVariation;

class ProductVariationRepository implements ProductVariationRepositoryInterface
{
    public function all()
    {
        return ProductVariation::all();
    }

    public function get($where = [], $with = [])
    {
        $query = ProductVariation::query();
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
        return ProductVariation::find($id);
    }

    public function first($where = [], $with = [])
    {
        $query = ProductVariation::query();
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
        return ProductVariation::create($attributes);
    }

    public function update($id, array $attributes)
    {
        $product = ProductVariation::findOrFail($id);

        $product->update($attributes);

        return $product;
    }

    public function firstOrCreate($id, array $attributes)
    {
        if ($variation = ProductVariation::find($id)) {
            return $variation;
        }

        $variation = ProductVariation::create($attributes);
        return $variation;
    }

    public function delete($id)
    {
        $product = ProductVariation::findOrFail($id);

        $product->delete();

        return true;
    }
}
