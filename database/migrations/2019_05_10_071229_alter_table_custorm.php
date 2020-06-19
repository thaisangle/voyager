<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCustorm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('type_role')->default('default');
            $table->string('vip')->default('default');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('path_icon');
            $table->string('icon_default')->nullable();
            $table->string('icon_select')->nullable();
            $table->string('icon_none')->nullable();
            $table->string('icon_bg_white')->nullable();
        });
        Schema::table('colors', function (Blueprint $table) {
            $table->integer('status')->default(0);
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
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
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type_role');
            $table->dropColumn('vip');
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->string('path_icon');
            $table->dropColumn('icon_default')->default(null);
            $table->dropColumn('icon_select')->default(null);
            $table->dropColumn('icon_none')->default(null);
        });

        Schema::dropIfExists('brands');

    }
}
