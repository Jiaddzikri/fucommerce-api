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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string("user_id", 20)->nullable();
            $table->string("province", 50)->nullable();
            $table->string("regency", 50)->nullable();
            $table->string("district", 50)->nullable();
            $table->longText("description")->nullable();
            $table->string("postal_code", 10)->nullable();
            $table->timestamp("created_at")->nullable();
            $table->timestamp("updated_at")->nullable();

            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("province")->references("name")->on("provinces");
            $table->foreign("regency")->references("name")->on("regencies");
            $table->foreign("district")->references("name")->on("districts");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
};
