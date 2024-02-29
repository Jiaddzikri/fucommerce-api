<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("products")->insert([
            "id" => uniqid(),
            "category_id" => "CAT99",
            "sub_category_1_id" => "SUBCAT99",
            "sub_category_2_id" => "SUBCAT999",
            "sub_category_3_id" => "SUBCAT9999",
            "user_id" => "65adf076da248",
            "name" => "Iphone 15 Pro Max 8/512",
            "price" => 25000000,
            "description" => "Hp mahal"
        ]);

        DB::table("products")->insert([
            "id" => uniqid(),
            "category_id" => "CAT99",
            "sub_category_1_id" => "SUBCAT99",
            "sub_category_2_id" => "SUBCAT999",
            "sub_category_3_id" => "SUBCAT9999",
            "user_id" => "65adf076da248",
            "name" => "Iphone 6s 2/32",
            "price" => 2000000,
            "description" => "Hp mahal (dulu)"
        ]);
    }
}
