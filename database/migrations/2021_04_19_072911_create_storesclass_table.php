<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresclassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storesclass', function (Blueprint $table) {
            $table->id()->comment('流水序號');
            $table->char('storeid',100)->unique()->comment('店家編號');
            $table->char('classid',100)->unique()->comment('類別編號');
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
        Schema::dropIfExists('storesclass');
    }
}
