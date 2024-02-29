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
        Schema::create('discussion_replies', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string("product_id", 20);
            $table->string("discussion_id", 20);
            $table->string("sender_id", 20);
            $table->string("receiver_id", 20);
            $table->text("content");
            $table->timestamp("created_at");
            $table->timestamp("updated_at")->nullable();
            $table->timestamp("deleted_at")->nullable();

            $table->foreign("discussion_id")->references("id")->on("product_discussion")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("product_id")->references("id")->on("products")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("sender_id")->references("id")->on("users");
            $table->foreign("receiver_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discussion_replies');
    }
};
