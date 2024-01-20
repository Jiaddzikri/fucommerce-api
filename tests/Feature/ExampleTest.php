<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from sessions");
        DB::delete("delete from users");
    }
    public function testRegister(): void
    {

        DB::table("users")
            ->insert([
                "id" => uniqid(),
                "username" => "sukuna",
                "email" => "sukuna@gmail.com",
                "role" => "seller",
                "password_hash" => Hash::make("rahasia")
            ]);
    }
}
