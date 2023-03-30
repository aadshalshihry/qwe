<?php

namespace App\Repositories;

use App\Models\Variation;

class VariationRepository implements VariationRepositoryInterface
{
    public function all()
    {
        return Variation::all();
    }

    public function get($where = [], $with = [])
    {
        $query = Variation::query();
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
        return Variation::find($id);
    }

    public function first($where = [], $with = []) {
        $query = Variation::query();
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
        return Variation::create($attributes);
    }

    public function update($id, array $attributes)
    {
        $variation = Variation::findOrFail($id);

        $variation->update($attributes);

        return $variation;
    }

    public function firstOrCreate($id, array $attributes)
    {
        if($variation = Variation::find($id)) {
            return $variation;
        }
        
        $variation = Variation::create($attributes);
        return $variation;
    }

    public function delete($id)
    {
        $variation = Variation::findOrFail($id);

        $variation->delete();

        return true;
    }
}
