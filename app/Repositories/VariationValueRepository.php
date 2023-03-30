<?php

namespace App\Repositories;

use App\Models\VariationValues;

class VariationValueRepository implements VariationValueRepositoryInterface
{
    public function all()
    {
        return VariationValues::all();
    }

    public function get($where = [], $with = [])
    {
        $query = VariationValues::query();
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
        return VariationValues::find($id);
    }

    public function first($where = [], $with = []) {
        $query = VariationValues::query();
        if(!empty($where)) {
            $query = $query->where($where);
        }

        if (!empty($with)) {
            $query = $query->with($with);
        }

        return $query->first();
    }

    public function create(array $attributes)
    {
        return VariationValues::create($attributes);
    }

    public function update($id, array $attributes)
    {
        $variation = VariationValues::findOrFail($id);

        $variation->update($attributes);

        return $variation;
    }

    public function firstOrCreate($id, array $attributes)
    {
        if($variation = VariationValues::find($id)) {
            return $variation;
        }
        
        $variation = VariationValues::create($attributes);
        return $variation;
    }

    public function delete($id)
    {
        $variation = VariationValues::findOrFail($id);

        $variation->delete();

        return true;
    }
}
