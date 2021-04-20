<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorescupsrecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storescupsrecords', function (Blueprint $table) {
            $table->id()->comment('流水序號');
            $table->char('storeid',100)->unique()->comment('店家編號');
            $table->integer('pullcup')->default(0)->comment('取杯數量');
            $table->integer('pushcup')->default(0)->comment('送杯數量');
            $table->timestamp('date')->useCurrent()->comment('收送時間戳記');
            $table->char('adminid',100)->comment('管理人員的帳號');
            $table->char('check',2)->default('N')->comment('確認章簽');
            $table->char('comment')->nullable()->comment('備註');
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
        Schema::dropIfExists('storescupsrecords');
    }
}
