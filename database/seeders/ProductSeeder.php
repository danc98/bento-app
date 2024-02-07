<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'item_name' => 'Taco Box',
            'plu_cd' => '1234567890',
            'item_price' => '450',
            'item_desc' => 'Ground beef tacos, in a bento box? Nuts!',
            'item_img' => 'taco_box.png',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
            'category_id' => '2',
        ]);

        DB::table('products')->insert([
            'item_name' => 'Steak Box',
            'plu_cd' => '1231231230',
            'item_price' => '1000',
            'item_desc' => 'A nice big slab of American beef.',
            'item_img' => 'steak_box.png',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
            'category_id' => '2',
        ]);

        DB::table('products')->insert([
            'item_name' => 'Tempura Box',
            'plu_cd' => '3295423940',
            'item_price' => '650',
            'item_desc' => 'A classic Japanese tempura box. Includes lots of chicken, lotus root, and pumpkin!',
            'item_img' => 'tempura_box.png',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
            'category_id' => '4',
        ]);

        DB::table('products')->insert([
            'item_name' => 'Tonkatsu Box',
            'plu_cd' => '3295423945',
            'item_price' => '500',
            'item_desc' => 'Japanese pork tonkatsu. Comes with tonkatsu sauce and mustard to really ramp up the flavor.',
            'item_img' => 'tonkatsu_box.png',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
            'category_id' => '3',
        ]);

        DB::table('products')->insert([
            'item_name' => 'Sushi Box',
            'plu_cd' => '3295423925',
            'item_price' => '900',
            'item_desc' => 'A Japanese style sushi bento box. The taste is so good you would think it was fresh caught.',
            'item_img' => 'sushi_box.png',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
            'category_id' => '1',
        ]);

        DB::table('products')->insert([
            'item_name' => 'Fish Box',
            'plu_cd' => '3295423925',
            'item_price' => '750',
            'item_desc' => 'A griled salmon bento box. Healthy and filling at the same time! Even includes a lemon.',
            'item_img' => 'fish_box.png',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
            'category_id' => '1',
        ]);

        DB::table('products')->insert([
            'item_name' => 'Test Product',
            'plu_cd' => '3295423925',
            'item_price' => '750',
            'item_desc' => 'A test product!',
            'item_img' => 'placeholder.png',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
            'category_id' => '5',
        ]);

        DB::table('products')->insert([
            'item_name' => 'Inactive',
            'plu_cd' => '54363467454',
            'item_price' => '404',
            'item_desc' => 'I don\'t have any active labels, so you won\'t see me!',
            'item_img' => 'placeholder.png',
            'created_at' => '2023-10-01 12:00:00',
            'updated_at' => '2023-10-01 12:00:00',
            'category_id' => '5',
        ]);
    }
}
