<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->create_users();
        //$this->update_users();
    }

    public function update_users(){
        if (Schema::hasTable('users')){
            // Schema::table('users',function(Blueprint $table){
            //     $table->string('address')->nullable();
            // });
            // Schema::table('users',function(Blueprint $table){
            //     $table->integer('cart_id')->unsigned()->nullable();
            // });
        }
    }

    /**
     * MY DEFINE 
     */
    public function create_users(){
        if (!Schema::hasTable('users')){
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('avatar')->nullable();
                $table->date('birthday')->nullable();
                $table->string('phone')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
}
