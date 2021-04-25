<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id()->comment('流水序號');
            $table->char('storeid',100)->unique()->comment('店家編號');
            $table->char('storename')->comment('店家名稱');
            $table->char('businessid',20)->unique()->default('00000000')->comment('店家統一編號');
            $table->char('qrcodeid',16)->unique()->comment('店家 QRcode 編碼');
            $table->json('phoneno')->comment('店家連絡電話');
            $table->json('email')->comment('店家連絡用Email');
            $table->enum('lock',['Y','N'])->default('Y')->comment('凍結帳號與否');
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
        Schema::dropIfExists('stores');
    }
}
