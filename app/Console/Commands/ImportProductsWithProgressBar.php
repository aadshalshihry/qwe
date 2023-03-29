<?php

namespace App\Console\Commands;

use App\Imports\ProductsImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Excel;
use PDO;

class ImportProductsWithProgressBar extends Command
{
    /**
     * @var string
     */
    protected $signature = 'import:productsPB';

    /**
     * @var string
     */
    protected $description = 'Imports products into database';

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        $import = new ProductsImport();

        // (new ProductsImport)->import(base_path('products2.csv'), null, \Maatwebsite\Excel\Excel::CSV);
        $this->output->title('Starting import products');
        $import->withOutput($this->output)->import('products.csv', null, \Maatwebsite\Excel\Excel::CSV);
        $this->output->success('Import successful');

        info("Failed to import products");
        $import->failures();
        
        foreach ($import->failures() as $failure) {
            $failure->row(); // row that went wrong
            $failure->attribute(); // either heading key (if using heading row concern) or column index
            $failure->errors(); // Actual error messages from Laravel validator
            $failure->values(); // The values of the row that has failed.
        }
    }
}
