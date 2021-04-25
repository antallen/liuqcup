<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
use Brokenice\LaravelMysqlPartition\Models\Partition;
use Brokenice\LaravelMysqlPartition\Schema\Schema;
use Illuminate\Support\Facades\DB;
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
            $table->unsignedBigInteger('id')->comment('流水序號');
            $table->char('storeid',100)->comment('店家編號');
            $table->integer('pullcup')->default(0)->comment('取杯數量');
            $table->integer('pushcup')->default(0)->comment('送杯數量');
            $table->dateTime('date')->useCurrent()->comment('收送時間戳記');
            $table->char('adminid',100)->comment('管理人員的帳號');
            $table->char('check',2)->default('N')->comment('確認章簽');
            $table->char('comment')->nullable()->comment('備註');
            $table->timestamps();
            $table->primary(['date','id']);
            $table->index(['storeid','adminid']);
        });

        Schema::table('storescupsrecords', function (Blueprint $table) {
            //進行分表工作
            Schema::partitionByYearsAndMonths('storescupsrecords', 'date',2019,2030);

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
        /*
        Schema::table('storescupsrecords', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::drop('storescupsrecords');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        });
        */
    }
}
