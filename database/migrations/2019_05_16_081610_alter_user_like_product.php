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
            $table->unsignedBigInteger('of_user_id');
            $table->foreign('of_user_id')->references('id')->on('users');
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
            $table->dropForeign(['of_user_id']);
            $table->dropColumn('of_user_id');
        });
    }
}
