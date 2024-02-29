<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategories3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("sub_categories_3")
            ->insert([
                "id" => "SUBCAT05",
                "sub_category_2_id" => "SUBCAT03",
                "name" => "lenovo legion"
            ]);

        DB::table("sub_categories_3")
            ->insert([
                "id" => "SUBCAT06",
                "sub_category_2_id" => "SUBCAT04",
                "name" => "iphone"
            ]);
    }
}
