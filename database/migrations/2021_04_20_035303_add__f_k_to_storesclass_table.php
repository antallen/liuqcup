<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFKToStoresclassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('storesclass', function (Blueprint $table) {
            //storesclass(storeid) -> stores(storeid)
            $table->foreign('storeid')->references('storeid')->on('stores')
            ->onUpdate('cascade')->onDelete('cascade');

            //storesclass(classid) -> classes(classid)
            $table->foreign('classid')->references('classid')->on('classes')
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
        Schema::table('storesclass', function (Blueprint $table) {
            //刪除 storesclass(storeid) -> stores(storeid)
            $table->dropForeign('storesclass_storeid_foreign');

            //刪除 storesclass(classid) -> classes(classid)
            $table->dropForeign('storesclass_classid_foreign');
        });
    }
}
