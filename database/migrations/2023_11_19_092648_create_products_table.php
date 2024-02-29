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
        Schema::create('products', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string("category_id", 20)->nullable();
            $table->string("sub_category_1_id", 20)->nullable();
            $table->string("sub_category_2_id", 20)->nullable();
            $table->string("sub_category_3_id", 20)->nullable();
            $table->string("user_id", 20)->nullable();
            $table->string("inventory_id", 20)->nullable();
            $table->string("discount_id", 20)->nullable();
            $table->string("name", 50);
            $table->string("slug", 100);
            $table->bigInteger("price");
            $table->longText("description");
            $table->timestamp("created_at")->nullable();
            $table->timestamp("updated_at")->nullable();

            $table->foreign("category_id")->references("id")->on("categories");
            $table->foreign("sub_category_1_id")->references("id")->on("sub_categories_1");
            $table->foreign("sub_category_2_id")->references("id")->on("sub_categories_2");
            $table->foreign("sub_category_3_id")->references("id")->on("sub_categories_3");
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("inventory_id")->references("id")->on("product_inventory");
            $table->foreign("discount_id")->references("id")->on("product_discount");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
