<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('programme', 128)->nullable();
            $table->string('questionnaire', 128)->nullable();
            $table->string('business_name', 128)->nullable();
            $table->string('first_name', 64)->nullable();
            $table->string('surname', 64)->nullable();
            $table->string('email', 128);
            $table->mediumtext('extra')->nullable;
            $table->string('clear_password', 32)->nullable();
            $table->mediumtext('insert_sql')->nullable;
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
        Schema::dropIfExists('new_clients');
    }
}
