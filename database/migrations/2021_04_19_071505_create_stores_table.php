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
            $table->id();
            $table->char('storeid',100)->unique();
            $table->char('storeclassid',100)->unique();
            $table->char('storename');
            $table->char('businessid',20)->unique();
            $table->char('funcid',100);
            $table->char('salt',20)->unique();
            $table->char('token')->unique();
            $table->char('password',100);
            $table->json('phoneno');
            $table->json('email');
            $table->char('lock',2);
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
