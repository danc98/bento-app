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
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
        ]);

        DB::table('categories')->insert([
            'category_name' => 'Beef',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
        ]);

        DB::table('categories')->insert([
            'category_name' => 'Pork',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
        ]);

        DB::table('categories')->insert([
            'category_name' => 'Chicken',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
        ]);

        DB::table('categories')->insert([
            'category_name' => 'Test',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
        ]);
    }
}
