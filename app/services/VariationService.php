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

    public function createOrUpdate($product, $row) {
        $variations = json_decode($row['variations']);
        info($variations);
        if ($variations) {
            foreach ($variations as $variation) {
                // $variationRecord = Variation::where('name', $variation['name'])
                //     ->first();

                $variationRecord = $this->variationRepositoryInterface->first(['name' => $variation['name']]);
                // $variationValueRecord = VariationValues::where('value', $variation['value'])
                //     ->first();
                $variationValueRecord = $this->variationValueRepositoryInterface->first(['value' => $variation['value']]);

                // $productVarition = ProductVariation::with('variationValue')
                // ->where('product_id', $row['id'])
                //     ->where('varition_id', $variationRecord->id)
                //     ->where('variation_values_id', $variationValueRecord->id)
                //     ->first();
                $productVarition = $this->productVariationRepositoryInterface->first([
                    ['product_id', '=', $row['id']],
                    ['varition_id', '=', $variationRecord->id],
                    ['variation_values_id', '=', $variationValueRecord->id],
                ]);

                if ($productVarition) {
                    $productVarition->additional_price = $row['price'];
                    $productVarition->quantity = !empty($row['quantity']) and isset($row['quantity']) ? $row['quantity'] : 0;
                    if ($productVarition->variationValue->value != $variationValueRecord->value) {
                        $productVarition->variation_values_id = $productVarition->variationValue->id;
                    }
                    $productVarition->save();
                } else {
                    if (!$variationRecord) {
                        $variationRecord = $this->variationRepositoryInterface->create([
                            'name' => $variation['name']
                        ]);
                    }

                    if (!$variationValueRecord) {
                        $variationValueRecord = $this->variationValueRepositoryInterface->create([
                            'value' => $variation['value'],
                            'variation_id' => $variationRecord->id
                        ]);
                    }
                    $productVarition = $this->productVariationRepositoryInterface->create([
                        'additional_price' => $row['price'],
                        'quantity' => !empty($row['quantity']) and isset($row['quantity']) ? $row['quantity'] : 0,
                        'product_id' => $product->id,
                        'variation_id' => $variationRecord->id,
                        'variation_values_id' => $variationValueRecord->id
                    ]);
                }
            }
        }
    }
}