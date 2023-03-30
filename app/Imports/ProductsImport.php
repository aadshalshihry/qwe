<?php
namespace App\Imports;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Variation;
use App\Models\VariationValues;
use App\Rules\InputNotNullable;
use App\Rules\SKUValidator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class ProductsImport implements ToModel, WithValidation, WithProgressBar, WithUpserts, WithHeadingRow, SkipsOnFailure, WithChunkReading
{
    use Importable, SkipsFailures;
    
    /**
     * @param array $row
     *
     * @return Product|null
     */
    public function model(array $row)
    {

        $product = Product::find($row['id']);

        if($product) {
            $product->name = $row['name'];
            $product->price = $row['price'];
            $product->currency = $row['currency'];
            // $product->quantity = !empty($row['quantity']) and isset($row['quantity']) ? $row['quantity'] : 0;
            $product->status = $row['status'];
            $product->save();

            $variations = json_decode($row['variations']);
            info($variations);
            if($variations) {
                foreach($variations as $variation) {
                    $variationRecord = Variation::where('name', $variation['name'])
                        ->first();
                    $variationValueRecord = VariationValues::where('value', $variation['value'])
                        ->first();
                    $productVarition = ProductVariation::with('variationValue')
                        ->where('product_id', $row['id'])
                        ->where('varition_id', $variationRecord->id)
                        ->where('variation_values_id', $variationValueRecord->id)
                        ->first();

                    if($productVarition) {
                        $productVarition->additional_price = $row['price'];
                        $productVarition->quantity = !empty($row['quantity']) and isset($row['quantity']) ? $row['quantity'] : 0;
                        if($productVarition->variationValue->value != $variationValueRecord->value) {
                            $productVarition->variation_values_id = $productVarition->variationValue->id;
                        }
                        $productVarition->save();
                    } else {
                        if(!$variationRecord) {
                            $variationRecord = Variation::create([
                                'name' => $variation['name']
                            ]);
                        }

                        if(!$variationValueRecord) {
                            $variationValueRecord = VariationValues::create([
                                'value' => $variation['value'],
                                'variation_id' => $variationRecord->id
                            ]);
                        }
                        $productVarition = ProductVariation::create([
                            'additional_price' => $row['price'],
                            'quantity' => !empty($row['quantity']) and isset($row['quantity']) ? $row['quantity'] : 0,
                            'product_id' => $product->id,
                            'variation_id' => $variationRecord->id,
                            'variation_values_id' => $variationValueRecord->id
                        ]);
                    }
                }
            }
        } else {
            $product = Product::create([
                'id' => $row['id'],
                'name' => $row['name'],
                'sku' => $row['sku'],
                'price' => $row['price'],
                'currency' => $row['currency'],
                // 'variations' => $row['variations'],
                // 'quantity' => !empty($row['quantity']) and isset($row['quantity']) ? $row['quantity'] : 0,
                'status' => $row['status']
            ]);

        }
        return $product;
    }

    

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'id';
    }

    public function rules(): array
    {
        return [
            'name' => function ($attribute, $value, $onFailure) {
                if ($value == '') {
                    $onFailure('Name is required!');
                }
            },
            'sku' => ['required'],
            'price' => ['numeric']
        ];
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
