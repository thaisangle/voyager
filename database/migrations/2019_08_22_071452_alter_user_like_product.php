<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserLikeProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('user_like_product', function (Blueprint $table) {
            $table->unsignedBigInteger('of_product_id');
            $table->foreign('of_product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('user_like_product', function (Blueprint $table) {
            $table->dropForeign(['of_product_id']);
            $table->dropColumn('of_product_id');
        });
    }
}
