<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    public function all();
    public function get($where = [], $with = []);
    public function find($id);
    public function first($where = [], $with = []);
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function firstOrCreate($id, array $attributes);
    public function delete($id);
}