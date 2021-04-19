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
            $table->id();
            $table->char('storeid',100)->unique();
            $table->integer('pullcup')->default(0);
            $table->integer('pushcup')->default(0);
            $table->timestamp('date')->useCurrent();
            $table->char('adminid',100);
            $table->char('check',2)->default('N');
            $table->char('comment')->nullable();
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
