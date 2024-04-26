<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        //run .sql file
        $path = base_path('database/sql/items.sql');
        $sql = file_get_contents($path);
        \DB::unprepared($sql);
    }
}
