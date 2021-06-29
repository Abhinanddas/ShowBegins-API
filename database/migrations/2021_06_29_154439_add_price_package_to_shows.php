<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricePackageToShows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->bigInteger('pricing_package_master_id')->nullable()->default(null)->unsigned();
            $table->foreign('pricing_package_master_id')->references('id')->on('pricing_package_masters');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->dropColumn('pricing_package_master_id');
        });
    }
}
