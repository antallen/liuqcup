<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFKToStoresagentidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('storesagentids', function (Blueprint $table) {
            //
            $table->foreign('storeid')->references('storeid')->on('stores')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('storesagentids', function (Blueprint $table) {
            //
            $table->dropForeign('storesagentids_storeid_foreign');
        });
    }
}
