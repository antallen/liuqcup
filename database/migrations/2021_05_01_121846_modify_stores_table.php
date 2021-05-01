<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            //新增地址欄位
            $table->char('address')->nullable()->comment('店家地址');

            //修改欄位設定
            $table->string('storename',150)->change();
            $table->json('phoneno')->nullable()->change();
            $table->json('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            //移除欄位
            $table->dropColumn('address');
        });
    }
}
