<?php

namespace App\Jobs;

use App\Models\Product;
use App\Mail\SendMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProductImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $insertedProducts;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $insertedProducts)
    {
        $this->data = $data;
        $this->insertedProducts = $insertedProducts;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $key => $value) {
            try{
                if(!in_array($value['product_id'], $this->insertedProducts))
                {
                    Product::create($value);
                }
            } catch(\Exception $e){
                return response()->json([
                        'success' => false,
                        'message' => 'Product could not be inserted'
                ], 500);
            }
        }
    }
}
