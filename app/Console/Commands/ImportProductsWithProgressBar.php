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
    protected $signature = 'import:productsPB {fileName?}';

    /**
     * @var string
     */
    protected $description = 'Imports products into database with validation and progress bar';

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
        $fileName = $this->getCmdArgs();

        $import = new ProductsImport();

        $this->output->title('Starting import products');
        $import->withOutput($this->output)->import($fileName, null, \Maatwebsite\Excel\Excel::CSV);
        $this->output->success('Import successful');
        
        foreach ($import->failures() as $failure) {
            // LOG: all failuers
            $failure->row(); // row that went wrong
            $failure->attribute(); // either heading key (if using heading row concern) or column index
            $failure->errors(); // Actual error messages from Laravel validator
            $failure->values(); // The values of the row that has failed.
        }
    }

    private function getCmdArgs(): string
    {
        return $this->argument('fileName') ?? 'products.csv';
    }
}
