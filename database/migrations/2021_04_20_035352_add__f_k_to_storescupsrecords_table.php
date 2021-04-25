<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFKToStorescupsrecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::table('storescupsrecords', function (Blueprint $table) {
            //storescupsrecords(storeid) -> stores(storeid)
            $table->foreign('storeid')->references('storeid')->on('stores')
            ->onUpdate('cascade')->onDelete('no action');

            //storescupsrecords(adminid) -> accounts(adminid)
            $table->foreign('adminid')->references('adminid')->on('accounts')
            ->onUpdate('cascade')->onDelete('no action');
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
        Schema::table('storescupsrecords', function (Blueprint $table) {
            //刪除 storescupsrecords(storeid) -> stores(storeid)
            $table->dropForeign('storescupsrecords_storeid_foreign');

            //刪除 storescupsrecords(adminid) -> accounts(adminid)
            $table->dropForeign('storescupsrecords_adminid_foreign');
        });
        */
    }
}
