<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique()->nullable();
            $table->dateTime('date')->nullable();
            $table->string('reference')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->boolean('received')->default(0);
            $table->text('notes')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('supplier_id')
                ->references('id')->on('suppliers')
                ->onDelete('cascade');

        });

        Schema::create('purchase_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('cost', 20, 2)->nullable();
            $table->decimal('sub_total', 20, 2)->nullable();
            $table->timestamps();

            $table->foreign('purchase_id')
                ->references('id')->on('purchases')
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
        Schema::dropIfExists('purchase_products');
        Schema::dropIfExists('purchases');
    }
}
