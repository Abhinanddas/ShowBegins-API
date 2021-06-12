<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_refund_initiated')->default(false);
            $table->boolean('is_refunded')->default(false);
            $table->integer('num_of_tickets');
            $table->float('amount');
            $table->bigInteger('show_id')->unsigned();
            $table->bigInteger('screen_id')->unsigned();
            $table->bigInteger('movie_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('shows');
            $table->foreign('screen_id')->references('id')->on('screens');
            $table->foreign('movie_id')->references('id')->on('movies');
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
        Schema::dropIfExists('purchase_orders');
    }
}
