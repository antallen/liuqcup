<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresagentidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storesagentids', function (Blueprint $table) {
            //
            $table->integer('id');
            $table->char('agentid',50)->unique()->comment('店家管理人員帳號');
            $table->char('agentname',50)->nullable()->comment('店家管理人員姓名');
            $table->char('agentphone',10)->comment('店家管理人員手機號碼');
            $table->char('storeid',100)->comment('店家編號');
            $table->char('salt',20)->unique()->comment('加密用的 Hash Key');
            $table->char('token')->unique()->comment('店家管理人員的 Key');
            $table->char('password',100)->comment('店家管理人員密碼');
            $table->enum('lock',['Y','N'])->default('N')->comment('凍結帳號與否');
            $table->timestamps();
            $table->primary(['id','agentphone']);
            $table->autoIncrementingStartingValues('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storesagentids');

    }
}
