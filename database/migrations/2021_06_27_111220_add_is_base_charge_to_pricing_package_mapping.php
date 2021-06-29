<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsBaseChargeToPricingPackageMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pricing_package_mappings', function (Blueprint $table) {
            $table->boolean('is_base_charge')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pricing_package_mappings', function (Blueprint $table) {
            $table->dropColumn('is_base_charge');
        });
    }
}
