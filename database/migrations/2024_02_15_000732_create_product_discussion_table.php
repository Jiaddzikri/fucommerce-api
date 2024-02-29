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
        Schema::create('product_discussion', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string("product_id", 20);
            $table->string("sender_id", 20);
            $table->string("receiver_id", 20)->nullable();
            $table->text("content");
            $table->string("file_path", 30)->nullable();
            $table->timestamp("created_at");
            $table->timestamp("updated_at")->nullable();
            $table->timestamp("deleted_at")->nullable();

            $table->foreign("sender_id")->references("id")->on("users");
            $table->foreign("receiver_id")->references("id")->on("users");
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
        Schema::dropIfExists('product_discussion');
    }
};
