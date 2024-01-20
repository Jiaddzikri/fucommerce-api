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
        Schema::create('users', function (Blueprint $table) {
            $table->string("id", 20)->primary();
            $table->string('username', 50);
            $table->string('email', 60)->unique();
            $table->string("phone_number", 13)->nullable();
            $table->string("store_name", 20)->nullable();
            $table->string("store_domain", 40)->nullable();
            $table->enum("role", ["customer", "seller", "admin"]);
            $table->string("password_hash", 80);
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
        Schema::dropIfExists('users');
    }
};
