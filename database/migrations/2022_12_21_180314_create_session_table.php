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
        Schema::create('sessions', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string("user_id", 20)->nullable();
            $table->string("session_key")->nullable();
            $table->string("access_token", 250)->nullable();
            $table->timestamp("updated_at");
            $table->timestamp("created_at");
            $table->timestamp("expires");

            $table->foreign("user_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
};
