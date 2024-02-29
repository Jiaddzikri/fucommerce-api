<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("categories")
            ->insert([
                "id" => "CAT01",
                "name" => "komputer & latop ",
            ]);
        DB::table("categories")
            ->insert([
                "id" => "CAT02",
                "name" => "Handphone & tablet ",
            ]);
    }
}
