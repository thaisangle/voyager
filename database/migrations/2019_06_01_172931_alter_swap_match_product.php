<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSwapMatchProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('match_product', function (Blueprint $table) {
            $table->integer('status_cofirm_swap')->default(0);
        });

        Schema::create('swap_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id_small');
            $table->unsignedBigInteger('product_id_big');
            $table->foreign('product_id_small')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id_big')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('match_product', function (Blueprint $table) {
            $table->dropColumn('status_cofirm_swap');
        });

        Schema::dropIfExists('swap_product');
        
    }
}
