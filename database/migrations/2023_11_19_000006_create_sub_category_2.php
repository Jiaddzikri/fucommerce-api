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
        Schema::create('sub_categories_2', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string("sub_category_1_id", 20)->nullable();
            $table->string("name", 40);
            $table->timestamp("updated_at")->nullable();
            $table->timestamp("created_at")->nullable();

            $table->foreign("sub_category_1_id")->references("id")->on("sub_categories_1")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_categories_2');
    }
};
