<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFKToStoresfunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('storesfunctions', function (Blueprint $table) {
            //storesfunctions(storeid) -> stores(storeid)
            $table->foreign('storeid')->references('storeid')->on('stores')
            ->onUpdate('cascade')->onDelete('cascade');

            //storesfunctions(funcid) -> functions(funcid)
            $table->foreign('funcid')->references('funcid')->on('functions')
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
        Schema::table('storesfunctions', function (Blueprint $table) {
            // 刪除 storesfunctions(storeid) -> stores(storeid)
            $table->dropForeign('storesfunctions_storeid_foreign');

            // 刪除 storesfunctions(funcid) -> functions(funcid)
            $table->dropForeign('storesfunctions_funcid_foreign');
        });
    }
}
