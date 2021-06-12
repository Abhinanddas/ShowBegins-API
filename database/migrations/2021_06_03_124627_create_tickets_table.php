<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('seat_id');
            $table->boolean('is_deleted')->default(false);
            $table->bigInteger('show_id')->unsigned();
            $table->bigInteger('screen_id')->unsigned();
            $table->bigInteger('movie_id')->unsigned();
            $table->bigInteger('purchase_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('shows');
            $table->foreign('screen_id')->references('id')->on('screens');
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->foreign('purchase_id')->references('id')->on('purchase_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
