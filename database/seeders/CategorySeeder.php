<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'category_name' => 'Fish',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
        ]);

        DB::table('categories')->insert([
            'category_name' => 'Beef',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
        ]);

        DB::table('categories')->insert([
            'category_name' => 'Pork',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
        ]);

        DB::table('categories')->insert([
            'category_name' => 'Chicken',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
        ]);

        DB::table('categories')->insert([
            'category_name' => 'Test',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
        ]);
    }
}
