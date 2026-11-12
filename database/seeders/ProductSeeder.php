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
        $categories = DB::table('categories')->pluck('category_id', 'name');

        $now = now();

        DB::table('products')->insert([
            [
                'sku' => 'SPR-001', 
                'name' => 'Kale Seeds',
                'description' => 'After 6 days they will mature into Kale.',
                'price' => 70,
                // 'image_url' => '#',
                'category_id' => $categories['Spring'],
                'stock_qty' => 100,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sku' => 'SUM-001', 
                'name' => 'Red Cabbage Seeds',
                'description' => 'After 9 days they will mature into Red Cabbage.',
                'price' => 100,
                // 'image_url' => '#',
                'category_id' => $categories['Summer'],
                'stock_qty' => 100,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sku' => 'FAL-001', 
                'name' => 'Cranberry Seeds',
                'description' => 'After 7 days they will mature into Cranberries.',
                'price' => 240,
                // 'image_url' => '#',
                'category_id' => $categories['Fall'],
                'stock_qty' => 100,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
