<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsGroupsTable extends Migration
{

    public function up()
    {
        Schema::create('products_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_product_id')->nullable();
            $table->foreign('parent_product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('child_product_id')->nullable();
            $table->integer('quantity');

        });
    }


    public function down()
    {
        Schema::dropIfExists('products_groups');
    }
}
