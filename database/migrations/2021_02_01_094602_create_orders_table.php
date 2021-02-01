<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('price')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_mail');
            $table->string('customer_address')->nullable();
            $table->string('payment')->nullable();
            $table->string('delivery')->nullable();
            $table->text('form_order')->nullable();
            $table->longText('body')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
