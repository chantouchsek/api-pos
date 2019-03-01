<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name', 255)->nullable();
            $table->string('code')->nullable();
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->decimal('cost', 20, 2)->nullable();
            $table->decimal('price', 20, 2)->nullable();
            $table->date('imported_date')->nullable();
            $table->date('expired_at')->nullable();
            $table->integer('tax_rate')->nullable()->default(0);
            $table->enum('tax_method', ['Inclusive', 'Exclusive'])->default('Inclusive');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->decimal('qty', 20, 2)->default(0);
            $table->tinyInteger('qty_method')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')->on('categories')
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
        Schema::dropIfExists('products');
    }
}
