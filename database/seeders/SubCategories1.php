<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategories1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("sub_categories_1")
            ->insert([
                "id" => "SUBCAT01",
                "category_id" => "CAT01",
                "name" => "laptop"
            ]);
        DB::table("sub_categories_1")
            ->insert([
                "id" => "SUBCAT02",
                "category_id" => "CAT02",
                "name" => "handphone"
            ]);
    }
}
