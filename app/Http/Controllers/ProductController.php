<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Jobs\ProductImportJob;
use App\Jobs\ProductDeleteJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Import product data from XML file.
     *
     */
    public function importProducts($key, $value)
    {

        $feed_id = intval(strip_tags($value->feed_id->asXML()));
        $url = $value->feed_url;
        $productsXml = simplexml_load_file($url);

        $i = 0;
        $p = 0;
        $data = [];
        $product_ids = [];

        foreach ($productsXml as $key => $product) {            

            $pValue = $this->xmlToArray($product);

            $products = [
                'feed_id' => $feed_id,
                'dealer' => $pValue['forhandler'],
                'category_name' => $pValue['kategorinavn'],
                'brand' => isset($pValue['brand']) ? $pValue['brand'] : NULL,
                'product_name' => $pValue['produktnavn'],
                'product_id' => $pValue['produktid'],
                'ean' => isset($pValue['ean']) ? $pValue['ean'] : NULL,
                'description' => isset($pValue['beskrivelse']) ? $pValue['beskrivelse'] : NULL,
                'new_price' => isset($pValue['nypris']) ? $pValue['nypris'] : NULL,
                'gl_price' => isset($pValue['glpris']) ? $pValue['glpris'] : NULL,
                'freight' => isset($pValue['fragtomk']) ? $pValue['fragtomk'] : NULL,
                'stock_number' => isset($pValue['lagerantal']) ? $pValue['lagerantal'] : NULL,
                'image_url' => isset($pValue['billedurl']) ? $pValue['billedurl'] : NULL,
                'item_url' => isset($pValue['vareurl']) ? $pValue['vareurl'] : NULL       
            ];
            array_push($data, $products);
            array_push($product_ids, $pValue['produktid']);
        }

        $insertedProducts = $this->insertedProducts($feed_id, $product_ids);
        $missingProducts = $this->getMissingProducts($feed_id, $product_ids);

        

        $productChunks = array_chunk($data, 500);

        if(!empty($missingProducts)){

            $missingProductChunks = array_chunk($missingProducts, 500);

            foreach ($missingProductChunks[$i] as $key => $missingProduct) {
                dispatch(new ProductDeleteJob($feed_id, $missingProduct['product_id']))->delay(now()->addSeconds(2));
            }
            $i++;
        }

        foreach ($productChunks as $key => $product) {

             dispatch(new ProductImportJob($product, $insertedProducts))->delay(now()->addSeconds(2));
        }

        return Artisan::call('queue:work --stop-when-empty', []);;
    }

    public function xmlToArray ($xmlObj, $output = array () )
    {      
       foreach ( (array) $xmlObj as $index => $node )
       {
        $output[$index] = (is_object($node)) ? null: $node;
       }
      return $output;
    }

    public function getMissingProducts ($feed_id, $product_ids)
    {      
        $missingProducts = Product::select('product_id')
        ->where('feed_id', $feed_id)
        ->whereNotIn('product_id', $product_ids)
        ->get();

        return $missingProducts->toArray();
    }

    public function insertedProducts ($feed_id, $product_ids)
    {
        $insertedProducts = Product::select('product_id')
        ->where('feed_id', $feed_id)
        ->whereIn('product_id', $product_ids)
        ->get();

        if(!$insertedProducts){
            return $insertedProducts = [];
        }else{
            $insertedProducts = array_map('current', $insertedProducts->toArray());
        }

        return $insertedProducts;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
