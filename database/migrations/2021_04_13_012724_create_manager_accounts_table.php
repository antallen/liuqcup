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
            $table->id();
            $table->char('adminid',100)->unique();
            $table->string('adminname');
            $table->char('password',100);
            $table->char('salt',20)->unique();
            $table->string('token')->unique();
            $table->integer('level');
            $table->char('phoneno',20)->unique();
            $table->char('email',100)->unique();
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
        Schema::dropIfExists('accounts');
    }
}
