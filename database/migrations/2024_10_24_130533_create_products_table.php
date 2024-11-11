<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock');
            $table->string('image_url');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
            $table->softDeletes(); // Adds the 'deleted_at' column

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products'); // Drops the table with all columns, including soft deletes
    }
}
