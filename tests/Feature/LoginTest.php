<?php

namespace Tests\Feature;


use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from sessions");
        DB::delete("delete from customers");
        
    }

  
}
