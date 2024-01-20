<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function testUpdate(): void
    {
        $response = $this->post("/api/products/659ed24014509", [
            "image_1" => fake()->imageUrl()
        ]);
        var_dump($response);
    }
}
