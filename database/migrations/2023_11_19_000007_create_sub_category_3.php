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
        Schema::create('sub_categories_3', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string("sub_category_2_id", 20)->nullable();
            $table->string("name");
            $table->timestamp("updated_at")->nullable();
            $table->timestamp("created_at")->nullable();

            $table->foreign("sub_category_2_id")->references("id")->on("sub_categories_2")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_categories_3');
    }
};
