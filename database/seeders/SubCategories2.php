<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategories2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("sub_categories_2")
            ->insert([
                "id" => "SUBCAT03",
                "sub_category_1_id" => "SUBCAT01",
                "name" => "laptop gaming"
            ]);

        DB::table("sub_categories_2")
            ->insert([
                "id" => "SUBCAT04",
                "sub_category_1_id" => "SUBCAT02",
                "name" => "IOS"
            ]);
    }
}
