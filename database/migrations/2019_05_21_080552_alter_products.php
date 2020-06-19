<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProducts extends Migration
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
            $table->text('add_note')->nullable();
            $table->integer('urgent_swap_status')->default(0);
            $table->integer('urgent_swap_start')->default(1550339492);
            $table->integer('urgent_swap_end')->default(1550339492);
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
            $table->dropColumn('add_note');
            $table->dropColumn('urgent_swap_status');
            $table->dropColumn('urgent_swap_start');
            $table->dropColumn('urgent_swap_end');
        });
    }
}
