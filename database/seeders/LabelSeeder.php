<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('labels')->insert([
            'product_id' => '1',
            'prod_datetime' => '2023-10-01 11:00:00',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
            'pack_status' => '1',
        ]);

        DB::table('labels')->insert([
            'product_id' => '1',
            'prod_datetime' => '2023-10-01 12:00:00',
            'valid_datetime' => '2023-10-01 13:00:00',
            'update_datetime' => '2023-10-01 13:00:00',
            'pack_status' => '1',
        ]);

        DB::table('labels')->insert([
            'product_id' => '1',
            'prod_datetime' => '2023-10-01 12:00:00',
            'valid_datetime' => '2023-15-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
            'pack_status' => '0',
        ]);

        DB::table('labels')->insert([
            'product_id' => '2',
            'prod_datetime' => '2023-10-01 11:00:00',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
            'pack_status' => '1',
        ]);

        DB::table('labels')->insert([
            'product_id' => '3',
            'prod_datetime' => '2023-10-01 11:00:00',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
            'pack_status' => '1',
        ]);

        DB::table('labels')->insert([
            'product_id' => '4',
            'prod_datetime' => '2023-10-01 11:00:00',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
            'pack_status' => '1',
        ]);

        DB::table('labels')->insert([
            'product_id' => '5',
            'prod_datetime' => '2023-10-01 11:00:00',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
            'pack_status' => '1',
        ]);

        DB::table('labels')->insert([
            'product_id' => '5',
            'prod_datetime' => '2023-09-01 11:00:00',
            'valid_datetime' => '2023-09-05 12:00:00',
            'update_datetime' => '2023-09-10 12:00:00',
            'pack_status' => '2',
        ]);

        DB::table('labels')->insert([
            'product_id' => '6',
            'prod_datetime' => '2023-10-01 11:00:00',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
            'pack_status' => '1',
        ]);

        DB::table('labels')->insert([
            'product_id' => '6',
            'prod_datetime' => '2023-10-01 12:00:00',
            'valid_datetime' => '2023-10-01 13:00:00',
            'update_datetime' => '2023-10-01 13:00:00',
            'pack_status' => '1',
        ]);

        DB::table('labels')->insert([
            'product_id' => '6',
            'prod_datetime' => '2023-10-01 13:00:00',
            'valid_datetime' => '2023-10-01 14:00:00',
            'update_datetime' => '2023-10-01 14:00:00',
            'pack_status' => '1',
        ]);

        DB::table('labels')->insert([
            'product_id' => '7',
            'prod_datetime' => '2023-10-01 11:00:00',
            'valid_datetime' => '2023-10-01 12:00:00',
            'update_datetime' => '2023-10-01 12:00:00',
            'pack_status' => '1',
        ]);

        DB::table('labels')->insert([
            'product_id' => '8',
            'prod_datetime' => '2023-08-01 12:00:00',
            'valid_datetime' => '2023-08-05 12:00:00',
            'update_datetime' => '2023-08-28 12:00:00',
            'pack_status' => '2',
        ]);
    }
}
