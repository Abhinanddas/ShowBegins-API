<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewColumnsMovies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->string('name');
            $table->string('thumbnail')->nullable();
            $table->string('is_deleted')->default(false);
            $table->string('is_active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('thumbnail');
            $table->dropColumn('is_deleted');
            $table->dropColumn('is_active');
        });
    }
}
