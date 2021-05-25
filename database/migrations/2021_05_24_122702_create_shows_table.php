<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->bigInteger('screen_id')->unsigned();
            $table->foreign('screen_id')->references('id')->on('screens');
            $table->timestamp('show_time')->nullable(true);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_house_full')->default(false);
            $table->boolean('is_show_cancelled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shows');
    }
}
