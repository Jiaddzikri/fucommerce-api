<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string("product_id", 20);
            $table->string("main_image_name", 50)->nullable();
            $table->string("main_image_path", 100)->nullable();
            $table->string("second_image_name", 50)->nullable();
            $table->string("second_image_path", 100)->nullable();
            $table->string("third_image_name", 50)->nullable();
            $table->string("third_image_path", 100)->nullable();
            $table->string("fourth_image_name", 50)->nullable();
            $table->string("fourth_image_path", 100)->nullable();

            $table->foreign("product_id")->references("id")->on("products");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
};
