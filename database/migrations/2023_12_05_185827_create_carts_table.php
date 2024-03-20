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
        Schema::create('carts', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string("session_id", 20)->nullable();
            $table->string("product_id", 20)->nullable();
            $table->integer("quantity")->nullable()->default(0);
            $table->timestamp("created_at")->nullable();
            $table->timestamp("updated_at")->nullable();

            $table->foreign("session_id")->references("id")->on("sessions");
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
        Schema::dropIfExists('carts');
    }
};
