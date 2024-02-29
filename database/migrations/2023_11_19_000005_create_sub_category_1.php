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
        Schema::create('sub_categories_1', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string("category_id", 20)->nullable();
            $table->string("name", 40);
            $table->timestamp("updated_at")->nullable();
            $table->timestamp("created_at")->nullable();

            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_categories_1');
    }
};
