<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagerAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id()->comment('流水序號');
            $table->char('adminid',100)->unique()->comment('管理人員的帳號');
            $table->string('adminname')->comment('管理人員的真實姓名');
            $table->char('password',100)->comment('管理人員的密碼');
            $table->char('salt',20)->unique()->comment('加密用的 Hash Key');
            $table->string('token')->unique()->comment('管理人員的 Key');
            $table->enum('level',[0,1,2])->default(2)->comment('管理人員等級碼');
            $table->char('phoneno',20)->unique()->comment('管理人員連絡電話');
            $table->char('email',100)->unique()->comment('管理人員連絡用Email');
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
        Schema::dropIfExists('accounts');
    }
}
