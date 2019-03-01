<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique()->nullable();
            $table->string('card_number')->unique()->nullable();
            $table->decimal('value', 20, 2)->nullable();
            $table->decimal('balance', 20, 2)->nullable();
            $table->decimal('price', 20, 2)->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();

            $table->softDeletes();

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
        Schema::dropIfExists('gift_cards');
    }
}
