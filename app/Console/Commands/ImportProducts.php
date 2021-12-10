<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProductController;
use Illuminate\Console\Command;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is in charge of importing products from the XML file into the database, updating and deleting products';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $product = new ProductController();

        $url = "https://crawl.obsidianmedia.dk/masterfeed.php";
        $xml = simplexml_load_file($url);

        foreach ($xml as $key => $value)
        {
            $product->importProducts($key, $value);
        }
    }
}
