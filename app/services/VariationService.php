<?php

namespace App\Services;

use App\Repositories\ProductVariationRepository;
use App\Repositories\ProductVariationRepositoryInterface;
use App\Repositories\VariationRepository;
use App\Repositories\VariationRepositoryInterface;
use App\Repositories\VariationValueRepository;
use App\Repositories\VariationValueRepositoryInterface;

class VariationService {

    private VariationRepositoryInterface $variationRepositoryInterface;
    private VariationValueRepositoryInterface $variationValueRepositoryInterface;
    private ProductVariationRepositoryInterface $productVariationRepositoryInterface;

    public function __construct()
    {

        /**
         * I could not use the dependency injection in this scenario
         * beause I have to pass the needed Repository to the construct
         * every time I init new instance of the class
         */
        $this->variationRepositoryInterface = new VariationRepository();
        $this->variationValueRepositoryInterface = new VariationValueRepository();
        $this->productVariationRepositoryInterface = new ProductVariationRepository();
    }

    public function createOrUpdate($product, $variations, $price, $quantity) {
        foreach ($variations as $key => $variation) {
            if (empty($variation->name) or empty($variation->value)) {
                continue;
            }

            $variationRecord = $this->variationRepositoryInterface->first(['name' => $variation->name]);
            $variationValueRecord = $this->variationValueRepositoryInterface->first(['value' => $variation->value]);

            $productVariation = null;
            if ($variationRecord and $variationValueRecord) {
                $productVariation = $this->productVariationRepositoryInterface->first([
                    ['product_id', '=', $product->id],
                    ['variation_id', '=', $variationRecord->id],
                    ['variation_values_id', '=', $variationValueRecord->id],
                ], ['variationValue']);
            }

            if (!empty($productVariation)) {
                if($key == 1) {
                    $productVariation->additional_price = 0;
                } else {
                    $productVariation->additional_price = $price;
                }
                
                $productVariation->quantity = $quantity;

                if ($productVariation->variationValue->value != $variationValueRecord->value) {
                    $productVariation->variation_values_id = $productVariation->variationValue->id;
                }
                $productVariation->save();
            } else {
                if (!$variationRecord) {
                    $variationRecord = $this->variationRepositoryInterface->create([
                        'name' => $variation->name
                    ]);
                }

                if (!$variationValueRecord) {
                    $variationValueRecord = $this->variationValueRepositoryInterface->create([
                        'value' => $variation->value,
                        'variation_id' => $variationRecord->id
                    ]);
                }
                if ($variationRecord and $variationValueRecord) {

                    $productVariation = $this->productVariationRepositoryInterface->create([
                        'additional_price' => $price,
                        'quantity' => $quantity,
                        'product_id' => $product->id,
                        'variation_id' => $variationRecord->id,
                        'variation_values_id' => $variationValueRecord->id
                    ]);
                }
            }
        }
    }
}