<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("products")->insert([
            "id" => "P002",
            "category_id" => "CAT01",
            "sub_category_1_id" => "SUBCAT01",
            "sub_category_2_id" => "SUBCAT02",
            "sub_category_3_id" => "SUBCAT03",
            "user_id" => "659e92eff21da",
            "name" => "JAS tuxedo",
            "price" => 200000,
            "description" => "Nyaman untuk digunakan"
        ]);
    }
}
