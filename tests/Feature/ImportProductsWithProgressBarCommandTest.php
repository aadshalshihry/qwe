<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImportProductsWithProgressBarCommandTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testProductImportCommand()
    {
        // Create a temporary CSV file with product data
        $csvFile = tempnam(sys_get_temp_dir(), 'productImportTest');
        $csvData = [
            ['id', 'name', 'sku', 'price', 'currency', 'variations', 'quantity', 'status'],
            [1, 'product1', 'sku1', '10.00', 'SAR', '[{"name":"variation1","value":"value1"}]', 10, 'sale'], // No Issue (New Record)
            [2, null, 'sku2', '10.00', 'SAR', '[{"name":"variation1","value":"value1"}]', 10, 'sale'], // Name Null
            [3, 'product3', null, '10.00', 'SAR', '[{"name":"variation1","value":"value1"}]', 10, 'sale'], // SKU Null
            [4, 'product4', 'sku4', null, 'SAR', '[{"name":"variation1","value":"value1"}]', 10, 'sale'], // Price Null
            [5, 'product5', 'sku5', 5, null, null, 1, 'sale'], // Currency Null
            [6, 'product6', 'sku6', 6, "SAR", null, null, 'sale'], // Price Null
            [7, 'product7', 'sku7', 7, "SAR", null, "null", 'sale'], // Price Null
            [7, null, null, null, null, null, null, null],
            [7, null, null, null, null, null, null, null],
        ];
        $fp = fopen($csvFile, 'w');
        foreach ($csvData as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        // Run the product import command with the temporary CSV file
        $this->artisan('import:productsPB', ['fileName' => $csvFile])
            // ->expectsOutput('Importing products from ' . $csvFile)
            ->assertExitCode(0);

        // Assert that the products were correctly imported into the database
        $this->successVariationProductInsert([
            'id' => 1,
            'name' => 'product1',
            'sku' => 'sku1',
            'price' => '10.00',
            'currency' => 'SAR',
            'status' => 'sale'
        ], [
            ['name' => 'variation1', 'value' => 'value1']
        ]);

        // Missing Product Name
        $this->assertDatabaseMissing('products', [
            'id' => 2,
            'name' => null,
            'sku' => 'sku2',
            'price' => '10.00',
            'currency' => 'SAR',
            'status' => 'sale'
        ]);

        // Missing Product SKU
        $this->assertDatabaseMissing('products', [
            'id' => 3,
            'name' => 'product3',
            'sku' => null,
            'price' => '10.00',
            'currency' => 'SAR',
            'status' => 'sale'
        ]);

        // Missing Product price
        $this->assertDatabaseMissing('products', [
            'id' => 4,
            'name' => 'product4',
            'sku' => 'sku4',
            'currency' => 'SAR',
            'status' => 'sale'
        ]);

        // Missing currency and variations
        $this->assertDatabaseHas('products', [
            'id' => 5,
            'name' => 'product5',
            'sku' => 'sku5',
            'price' => 5,
            'currency' => null,
            'status' => 'sale'
        ]);

        // Missing quantity
        $this->assertDatabaseMissing('products', [
            'id' => 6,
            'name' => 'product6',
            'sku' => 'sku6',
            'price' => null,
            'currency' => 'SAR',
            'status' => 'sale'
        ]);
    }

    private function successVariationProductInsert($product = [], $variations = []) {
        $this->assertDatabaseHas('products', $product);
        foreach($variations as $variation) {
            $this->assertDatabaseHas('variations', [
                'name' => $variation['name']
            ]);
            $this->assertDatabaseHas('variation_values', [
                'value' => $variation['value']
            ]);
        }
    }
}
