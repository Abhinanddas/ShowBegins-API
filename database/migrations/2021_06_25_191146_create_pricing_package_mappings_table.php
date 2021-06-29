<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingPackageMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_package_mappings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('pricing_package_master_id')->unsigned();
            $table->bigInteger('pricing_id')->unsigned();
            $table->foreign('pricing_package_master_id')->references('id')->on('pricing_package_masters');
            $table->foreign('pricing_id')->references('id')->on('pricings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_package_mappings');
    }
}
