<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributesSeeder extends Seeder
{
    public function run(): void
    {
        // ['ALL-001' => 1, 'SPR-001' => 2, 'SUM-001' => 3, 'FAL-001' => 4]
        $products = DB::table('products')->pluck('product_id', 'sku');

        DB::table('product_attributes')->insert([
            [
                'product_id' => $products['ALL-001'],
                'attr_name' => 'Color',
                'attr_value' => 'Green',
            ],
            [
                'product_id' => $products['SPR-001'],
                'attr_name' => 'Color',
                'attr_value' => 'Yellow',
            ],
            [
                'product_id' => $products['SUM-001'],
                'attr_name' => 'Color',
                'attr_value' => 'Pink',
            ],
            [
                'product_id' => $products['FAL-001'],
                'attr_name' => 'Color',
                'attr_value' => 'Purple',
            ],
        ]);
    }
}
