<?php
namespace App\Imports;

use App\Models\Product;
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
        return Product::updateOrCreate([
                'id' => $row['id'],
            ],[
                'name' => $row['name'],
                'sku' => $row['sku'],
                'price' => $row['price'],
                'currency' => $row['currency'],
                'variations' => $row['variations'],
                'quantity' => !empty($row['quantity']) and isset($row['quantity']) ? $row['quantity'] : 0,
                'status' => $row['status']
            ]);
    }

    

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'sku';
    }

    public function rules(): array
    {
        return [
            'name' => function ($attribute, $value, $onFailure) {
                if ($value == '') {
                    $onFailure('Name is required!');
                }
            },
            'sku' => ['required', 'unique:products', new SKUValidator],
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
