<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_refund_initiated')->default(false);
            $table->boolean('is_refunded')->default(false);
            $table->integer('num_of_items');
            $table->float('amount');
            $table->bigInteger('purchase_order_id')->unsigned();
            $table->bigInteger('pricing_id')->unsigned();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->foreign('pricing_id')->references('id')->on('pricings');
            $table->timestamp('refund_initiated_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_details');
    }
}
