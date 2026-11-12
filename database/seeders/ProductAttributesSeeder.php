<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributesSeeder extends Seeder
{
    public function run(): void
    {
        // ['SPR-001' => 1, 'SUM-001' => 2, 'FAL-001' => 3]
        $products = DB::table('products')->pluck('product_id', 'sku');

        DB::table('product_attributes')->insert([
            [
                'product_id' => $products['SPR-001'],
                'attr_name' => 'Color',
                'attr_value' => 'Green',
            ],
            [
                'product_id' => $products['SUM-001'],
                'attr_name' => 'Color',
                'attr_value' => 'Purple',
            ],
            [
                'product_id' => $products['FAL-001'],
                'attr_name' => 'Color',
                'attr_value' => 'Red',
            ],
        ]);
    }
}
