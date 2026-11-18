<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // SELECT category_id name FROM categories
        // gives ['Spring' => 1, 'Summer' => 2, 'Fall' => 3]
        $seasons = DB::table('seasons')->pluck('season_id', 'name');

        $now = now();

        DB::table('products')->insert([
            [
                'sku' => 'ALL-001', 
                'name' => 'Grass Starter',
                'description' => 'Place this on your farm to start a new patch of grass.',
                'price' => 100,
                // 'image_url' => '#',
                'season_id' => $seasons['Year-Round'],
                'stock_qty' => 100,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sku' => 'SPR-001', 
                'name' => 'Parsnip Seeds',
                'description' => 'Plant these in the spring. Takes 4 days to mature.',
                'price' => 20,
                // 'image_url' => '#',
                'category_id' => $seasons['Spring'],
                'stock_qty' => 100,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sku' => 'SUM-001', 
                'name' => 'Melon Seeds',
                'description' => 'Plant these in the summer. Takes 12 days to mature.',
                'price' => 80,
                // 'image_url' => '#',
                'category_id' => $seasons['Summer'],
                'stock_qty' => 100,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sku' => 'FAL-001', 
                'name' => 'Eggplant Seeds',
                'description' => 'Plant these in the fall. Takes 5 days to mature, and continues to produce after first harvest.',
                'price' => 20,
                // 'image_url' => '#',
                'category_id' => $seasons['Fall'],
                'stock_qty' => 100,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
