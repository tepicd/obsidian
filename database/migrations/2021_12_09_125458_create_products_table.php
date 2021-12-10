<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('feed_id');
            $table->string('dealer', 50);
            $table->text('category_name')->nullable();
            $table->string('brand', 50)->nullable();
            $table->text('product_name')->nullable();
            $table->string('product_id', 50);
            $table->string('ean', 50)->nullable();
            $table->text('description')->nullable();
            $table->float('new_price')->nullable();
            $table->float('gl_price')->nullable();
            $table->string('freight', 20)->nullable();
            $table->string('stock_number', 50)->nullable();
            $table->text('image_url')->nullable();
            $table->text('item_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['feed_id']);
            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
