<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable();
            $table->unsignedInteger('sale_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('amount', 20, 2)->nullable();
            $table->tinyInteger('paid_by')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('sale_id')
                ->references('id')->on('sales')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('sale_payments');
    }
}
