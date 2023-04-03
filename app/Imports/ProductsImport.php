<?php
namespace App\Imports;

use App\Enum\ProductStatusEnum;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Variation;
use App\Models\VariationValues;
use App\Rules\NotNullValidator;
use App\Rules\SKUValidator;
use App\Services\ProductService;
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
        return (new ProductService())->createOrUpdate($row);
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
            'price' => ['numeric', New NotNullValidator()],
            'quantity' => ['numeric', New NotNullValidator()],
            'status' => [new Enum(ProductStatusEnum::class)]
        ];
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
        // info($failures);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
