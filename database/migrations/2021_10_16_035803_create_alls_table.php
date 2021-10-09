<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->create_roles();
        $this->create_categories();
        $this->create_products();
        $this->create_collections();
        $this->create_carts();
        $this->create_detail_carts();
        $this->create_bills();
    }

    /**
     * MY DEFINE FUNCTION TO CREATE TABLE FOLLOW IDENTITY
     */
    public function create_roles()
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('role_name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
            // ADD FOREIGN KEY TO USERS
            Schema::table('users', function (Blueprint $table) {
                $table->integer('role_id')->unsigned();
                $table->foreign('role_id')->references('id')->on('roles');
            });
        }
    }

    public function create_comments()
    {
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->increments('id');
                $table->string('comment_title')->nullable();
                $table->text('comment_content');
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on('users');
                $table->integer('product_id')->unsigned();
                $table->foreign('product_id')->references('id')->on('products');
                $table->timestamps();
            });
        }
    }

    public function create_carts()
    {
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on('users');
                $table->timestamps();
            });
            Schema::table('users',function(Blueprint $table){
                $table->integer('cart_id')->unsigned()->nullable();
            });
        }
    }

    public function create_products()
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->increments('id');
                $table->string('product_code');
                $table->string('product_name');
                $table->string('product_link');
                $table->integer('product_price');
                $table->integer('product_percent');
                $table->text('product_describe');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('categories');
                $table->timestamps();
            });
        }
    }

    public function create_categories()
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('category_name');
                $table->string('category_picture');
                $table->timestamps();
            });
        }
    }

    public function create_bills()
    {
        if (!Schema::hasTable('bills')) {
            Schema::create('bills', function (Blueprint $table) {
                $table->increments('id');
                $table->string('address');
                $table->string('phone');
                $table->text('note');
                $table->integer('total_cost');
                $table->date('delivery_time');
                $table->integer('cart_id')->unsigned();
                $table->foreign('cart_id')->references('id')->on('carts');
                $table->timestamps();
            });
        }
    }

    public function create_detail_carts()
    {
        if (!Schema::hasTable('detail_carts')) {
            Schema::create('detail_carts', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('cart_id')->unsigned();
                $table->foreign('cart_id')->references('id')->on('carts');
                $table->integer('product_id')->unsigned();
                $table->foreign('product_id')->references('id')->on('products');
                $table->integer('product_amount');
                $table->timestamps();
            });
        }
    }

    public function create_collections()
    {
        if (!Schema::hasTable('collections')) {
            Schema::create('collections', function (Blueprint $table) {
                $table->increments('id');
                $table->string('product_picture');
                $table->integer('product_id')->unsigned();
                $table->foreign('product_id')->references('id')->on('products');
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
        Schema::dropIfExists('collections');
        Schema::dropIfExists('detail_carts');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('products');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('comments');
    }
}
