<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRenameColumnSwapUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['swap_user_id']);
            $table->dropColumn('swap_user_id');
            $table->unsignedBigInteger('old_user_id')->nullable();
            $table->foreign('old_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['old_user_id']);
            $table->dropColumn('old_user_id');
            $table->unsignedBigInteger('swap_user_id')->nullable();
            $table->foreign('swap_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
