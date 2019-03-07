<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable();
            $table->string('sale_number')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('user_id')->nullable()->comment('Sale Person');
            $table->dateTime('date')->nullable();
            $table->decimal('total', 20, 2)->nullable();
            $table->decimal('grand_total', 20, 2)->nullable();
            $table->decimal('paid', 20, 2)->nullable()->comment('Amount of total customer paid to');
            $table->decimal('tax', 20, 2)->nullable();
            $table->decimal('discount', 20, 2)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('customer_id')
                ->references('id')->on('customers')
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
        Schema::dropIfExists('sales');
    }
}
