<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;

class ProductService {

    private ProductRepositoryInterface $productRepositoryInterface;

    public function __construct()
    {

        /**
         * I could not use the dependency injection in this scenario
         * beause I have to pass the needed Repository to the construct
         * every time I init new instance of the class
         */
        $this->productRepositoryInterface = new ProductRepository();
    }

    public function createOrUpdate($row) {
        $variations = null;

        $product = $this->productRepositoryInterface->first(['sku' => $row['sku']]);
        
        
        if (array_key_exists('variations', $row)) {
            $variationsJson = json_decode($row['variations']);
            if (!empty($variationsJson))
                $variations = $variationsJson;
        }

        if ($product) {
            $product->name = $row['name'];
            $product->price = $row['price'];
            $product->currency = $row['currency'];
            $product->status = $row['status'];
            $product->save();

            if (!empty($variations)) {
                (new VariationService())->createOrUpdate($product, $variations, $row['price'], $row['quantity']);
            }
        } else {

            $data = [
                'id' => $row['id'],
                'name' => $row['name'],
                'sku' => $row['sku'],
                'price' => $row['price'],
                'currency' => $row['currency'],
                'status' => $row['status']
            ];

            $product = $this->productRepositoryInterface->create($data);
            if (!empty($variations)) {
                (new VariationService())->createOrUpdate($product, $variations, $row['price'], $row['quantity']);
            }
        }
        return $product;
    }
}