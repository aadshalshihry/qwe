<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Variation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportProductExternalApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:product-external-api {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Product from external api';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $response = Http::get($this->getCmdArgs());
        if($response->successful()) {
            foreach($response->json() as $product) {
                // Assumation: the name is exactly the same
                // Assumation: product has to be created before import work beacuse
                //             the definition of product need at least SKU
                $productRecord = Product::where('name', $product['name'])->first();
                if($productRecord) {
                    $productRecord->price = $product['price'];
                    if(empty($productRecord->image)) {
                        $productRecord->image = $product['image'];
                    }
                    $productRecord->save();
                    if(!empty($product['variations'])) {
                        foreach($product['variations'] as $variation) {
                            // Assumation: the name is exactly the same
                            $colorVariation = $this->getVariationRecord('color', $variation['color']);
                            $materialVariation = $this->getVariationRecord('material', $variation['material']);
                            if($colorVariation) {
                                $productVariation = ProductVariation::where('variation_id', $colorVariation->id)->first();
                                if($productVariation) {
                                    $productVariation->quantity = $variation['quantity'];
                                    $productVariation->additional_price = $variation['additional_price'];
                                    $productVariation->save();
                                    // log record update successful
                                    // send notification
                                } else {
                                    // save a log of the product variation does not exists
                                }
                            } else {
                                // save a log of the variation does not exists
                            }
                        }
                    }
                } else {
                    // save a log of the product does not exists
                }
            }
        }
    }

    private function getVariationRecord($name, $value) {
        return Variation::with('varitionValues')
                    ->where('name', $name)
                    ->whereHas('varitionValues', function($q) use ($value){
                        $q->where('value', $value);
                    })
                    ->first();
    }

    private function getCmdArgs(): string
    {
        return $this->argument('url') ?? 'https://5fc7a13cf3c77600165d89a8.mockapi.io/api/v5/products';
    }
}
