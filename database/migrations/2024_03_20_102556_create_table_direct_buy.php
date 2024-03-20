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
        Schema::create('table_direct_buy', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string("product_id", 20)->nullable();
            $table->string("session_id", 20)->nullable();
            $table->string("note", 100)->nullable();
            $table->integer("quantity")->nullable()->default(0);
            $table->timestamp("created_at")->nullable();
            $table->timestamp("updated_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_direct_buy');
    }
};
