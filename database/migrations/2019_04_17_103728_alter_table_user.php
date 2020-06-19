<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->string('social_token')->nullable();
            $table->string('social_id')->nullable();
            $table->string('avatar')->nullable();
            $table->string('type_social')->nullable();
            $table->timestamp('birth_day')->nullable();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('social_token');
            $table->dropColumn('social_id');
            $table->dropColumn('avatar');
            $table->dropColumn('type_social');
            $table->dropColumn('birth_day');
        });
    }
}
