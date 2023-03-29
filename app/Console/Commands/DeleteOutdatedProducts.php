<?php

namespace App\Console\Commands;

use App\Enum\ProductStatusEnum;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ProductRepositoryInterface;
use Exception;

class DeleteOutdatedProducts extends Command
{

    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
        parent::__construct($productRepository);
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-outdated-products {fileName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command soft deletes outdated products';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $fileName = $this->getCmdArgs();

        try {
            $contents = file_get_contents($fileName);
            $lines = explode("\n", $contents);
        } catch(Exception $e) {
            $this->error('Uncorrect File Name!');
            die();
        }

        $numberOfDeletedProduct = 0;
        $productsExistInFile = [];
        $jsonFilePath = 'public/uploads/productsExistInFile.json';
        if (Storage::exists($jsonFilePath)) {
            Storage::delete($jsonFilePath);
        }

        $this->info('Starting Deleting records with status deleted....');

        $bar = $this->output->createProgressBar(count($lines));
        foreach ($lines as $key => $line) {
            // Skip the header from the iteration
            if($key == 0) continue;

            // Remove unnecessary char from the row
            $line = str_replace("\r", '', $line);

            // convert row to a collections
            $row_data = explode(',', $line);


            $existProduct = $this->productRepository->find($row_data[0]);
            if($existProduct) {
                $productsExistInFile[] = $row_data[0];
                if($existProduct->status == ProductStatusEnum::DELETED) {
                    $existProduct->deleted_hint = "The product was deleted because of the synchronization process.";
                    $existProduct->save();
                    $existProduct->delete();
                    $numberOfDeletedProduct++;
                }
            }
            $bar->advance();
        }
        $bar->finish();

        $this->newLine();
        $this->info('Starting Deleting not existed records form the database....');
        $bar = $this->output->createProgressBar(count($productsExistInFile));
        $records = Product::whereNotIn('id', $productsExistInFile)->get();
        foreach ($records as $record) {
            if (!in_array($record->id, $productsExistInFile)) {
                $record->deleted_hint = "The product was deleted because of the synchronization process.";
                $record->save();
                $record->delete();
                $numberOfDeletedProduct++;
            }
            $bar->advance();
        }
        $bar->finish();

        $this->newLine();
        $this->info('Deleted ' . $numberOfDeletedProduct . ' products.');
    }

    private function getCmdArgs():string {
        return $this->argument('fileName') ?? 'products.csv';
    }
}
