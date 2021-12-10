<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductDeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $feed_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($feed_id, $data)
    {
        $this->data = $data;
        $this->feed_id = $feed_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            Product::where('feed_id', $this->feed_id)
            ->where('product_id', $this->data)
            ->delete();

        } catch(\Exception $e){
            return response()->json([
                    'success' => false,
                    'message' => 'Product could not be deleted'
            ], 500);
        }
    }
}
