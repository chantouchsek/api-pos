<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_products', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable();
            $table->unsignedInteger('sale_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->decimal('qty', 20, 2)->nullable();
            $table->decimal('price', 20, 2)->nullable();
            $table->decimal('sub_total', 20, 2)->nullable();
            $table->timestamps();

            $table->foreign('sale_id')
                ->references('id')->on('sales')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_products');
    }
}
