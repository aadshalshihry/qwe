<?php

namespace App\Console\Commands;

use App\Enum\ProductStatusEnum;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Console\Command;
use League\Csv\Reader;

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
    protected $signature = 'app:delete-outdated-products';

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
        $contents = file_get_contents('products2.csv');
        $lines = explode("\n", $contents);

        $numberOfDeletedProduct = 0;
        foreach ($lines as $key => $line) {
            // Skip the header from the iteration
            if($key == 0) continue;

            // Remove unnecessary char from the row
            $line = str_replace("\r", '', $line);

            // convert row to a collections
            $row_data = explode(',', $line);


            $existProduct = $this->productRepository->find($row_data[0]);
            if($existProduct and $existProduct->status == ProductStatusEnum::DELETED) {
                $existProduct->hint = "The product was deleted because of the synchronization process.";
                $existProduct->save();
                $existProduct->delete();
                $numberOfDeletedProduct++;
            } else {

            }
            die();
        }

    }
}
