<?php
namespace App\Imports;

use App\Enum\ProductStatusEnum;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Variation;
use App\Models\VariationValues;
use App\Rules\SKUValidator;
use App\Services\VariationService;
use Illuminate\Validation\Rules\Enum;
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

        $product = Product::where('id', $row['id'])->orWhere('sku', $row['sku'])->first();

        if($product) {
            $product->name = $row['name'];
            $product->price = $row['price'];
            $product->currency = $row['currency'];
            // $product->quantity = !empty($row['quantity']) and isset($row['quantity']) ? $row['quantity'] : 0;
            $product->status = $row['status'];
            $product->save();

            (new VariationService())->createOrUpdate($product, $row);
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

            (new VariationService())->createOrUpdate($product, $row);

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
            'sku' => ['required', new SKUValidator()],
            'price' => ['numeric'],
            'status' => [new Enum(ProductStatusEnum::class)]
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
