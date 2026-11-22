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
            // YEAR-ROUND PRODUCTS
            [ // Grass Starter
                'product_id' => $products['ALL-001'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '0',
            ],
            [ // Sugar
                'product_id' => $products['ALL-002'],
                'attr_name' => 'Color',
                'attr_value' => 'Blue',
            ],
            [ // Wheat Flour
                'product_id' => $products['ALL-003'],
                'attr_name' => 'Color',
                'attr_value' => 'Brown',
            ],
            [ // Rice
                'product_id' => $products['ALL-004'],
                'attr_name' => 'Color',
                'attr_value' => 'Beige',
            ],
            [ // Oil
                'product_id' => $products['ALL-005'],
                'attr_name' => 'Color',
                'attr_value' => 'Yellow',
            ],
            [ // Vinegar
                'product_id' => $products['ALL-006'],
                'attr_name' => 'Color',
                'attr_value' => 'Purple',
            ],
            [ // Basic Fertilizer
                'product_id' => $products['ALL-007'],
                'attr_name' => 'Color',
                'attr_value' => 'Brown',
            ],
            [ // Quality Fertilizer
                'product_id' => $products['ALL-008'],
                'attr_name' => 'Color',
                'attr_value' => 'Orange',
            ],
            [ // Basic Retaining Soil
                'product_id' => $products['ALL-009'],
                'attr_name' => 'Color',
                'attr_value' => 'Light Blue',
            ],
            [ // Quality Retaining Soil
                'product_id' => $products['ALL-010'],
                'attr_name' => 'Color',
                'attr_value' => 'Blue',
            ],
            [ // Speed-Gro
                'product_id' => $products['ALL-011'],
                'attr_name' => 'Color',
                'attr_value' => 'Purple',
            ],
            [ // Deluxe Speed-Gro
                'product_id' => $products['ALL-012'],
                'attr_name' => 'Color',
                'attr_value' => 'Pink',
            ],
            [ // Cherry Sapling
                'product_id' => $products['ALL-013'],
                'attr_name' => 'Harvesting Season',
                'attr_value' => 'Spring',
            ],
            [ // Apricot Sapling
                'product_id' => $products['ALL-014'],
                'attr_name' => 'Harvesting Season',
                'attr_value' => 'Spring',
            ],
            [ // Orange Sapling
                'product_id' => $products['ALL-014'],
                'attr_name' => 'Harvesting Season',
                'attr_value' => 'Summer',
            ],
            [ // Peach Sapling
                'product_id' => $products['ALL-015'],
                'attr_name' => 'Harvesting Season',
                'attr_value' => 'Summer',
            ],
            [ // Pomegranate Sapling
                'product_id' => $products['ALL-016'],
                'attr_name' => 'Harvesting Season',
                'attr_value' => 'Fall',
            ],
            [ // Apple Sapling
                'product_id' => $products['ALL-017'],
                'attr_name' => 'Harvesting Season',
                'attr_value' => 'Fall',
            ],
            [ // Catalogue
                'product_id' => $products['ALL-018'],
                'attr_name' => 'Color',
                'attr_value' => 'Brown',
            ],
            [ // Bouquet
                'product_id' => $products['ALL-019'],
                'attr_name' => 'Color',
                'attr_value' => 'Pink',
            ],
            // SPRING PRODUCTS
            [ // Parsnip Seeds
                'product_id' => $products['SPR-001'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '4',
            ],
            [ // Bean Starter
                'product_id' => $products['SPR-002'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '10',
            ],
            [
                'product_id' => $products['SPR-002'],
                'attr_name' => 'Regrow After Harvest?',
                'attr_value' => 'Yes',
            ],
            [ // Cauliflower Seeds
                'product_id' => $products['SPR-003'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '12',
            ],
            [ // Potato Seeds
                'product_id' => $products['SPR-004'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '6',
            ],
            [ // Tulip Bulb
                'product_id' => $products['SPR-005'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '6',
            ],
            [ // Kale Seeds
                'product_id' => $products['SPR-006'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '6',
            ],
            [
                'product_id' => $products['SPR-006'],
                'attr_name' => 'Harvest Tool',
                'attr_value' => 'Scythe',
            ],
            [ // Jazz Seeds
                'product_id' => $products['SPR-007'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '7',
            ],
            [ // Garlic Seeds
                'product_id' => $products['SPR-008'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '4',
            ],
            [ // Rice Shoot
                'product_id' => $products['SPR-009'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '4',
            ],
            [
                'product_id' => $products['SPR-009'],
                'attr_name' => 'Harvest Tool',
                'attr_value' => 'Scythe',
            ],
            [
                'product_id' => $products['SPR-009'],
                'attr_name' => 'Special Needs',
                'attr_value' => 'Nearby water source',
            ],
            // SUMMER PRODUCTS
            [ // Melon Seeds
                'product_id' => $products['SUM-001'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '12',
            ],
            [ // Tomato Seeds
                'product_id' => $products['SUM-002'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '11',
            ],
            [
                'product_id' => $products['SUM-002'],
                'attr_name' => 'Regrow After Harvest?',
                'attr_value' => 'Yes',
            ],
            [ // Blueberry Seeds
                'product_id' => $products['SUM-003'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '13',
            ],
            [
                'product_id' => $products['SUM-003'],
                'attr_name' => 'Regrow After Harvest?',
                'attr_value' => 'Yes',
            ],
            [ // Pepper Seeds
                'product_id' => $products['SUM-004'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '5',
            ],
            [
                'product_id' => $products['SUM-004'],
                'attr_name' => 'Regrow After Harvest?',
                'attr_value' => 'Yes',
            ],
            [ // Wheat Seeds
                'product_id' => $products['SUM-005'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '4',
            ],
            [
                'product_id' => $products['SUM-005'],
                'attr_name' => 'Harvest Tool',
                'attr_value' => 'Scythe',
            ],
            [ // Radish Seeds
                'product_id' => $products['SUM-006'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '6',
            ],
            [ // Poppy Seeds
                'product_id' => $products['SUM-006'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '7',
            ],
            [ // Spangle Seeds
                'product_id' => $products['SUM-007'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '8',
            ],
            [ // Hops Starter
                'product_id' => $products['SUM-008'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '11',
            ],
            [
                'product_id' => $products['SUM-008'],
                'attr_name' => 'Regrow After Harvest?',
                'attr_value' => 'Yes',
            ],
            [ // Corn Seeds
                'product_id' => $products['SUM-009'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '14',
            ],
            [
                'product_id' => $products['SUM-009'],
                'attr_name' => 'Regrow After Harvest?',
                'attr_value' => 'Yes',
            ],
            [ // Sunflower Seeds
                'product_id' => $products['SUM-010'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '8',
            ],
            [ // Red Cabbage Seeds
                'product_id' => $products['SUM-008'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '9',
            ],
            // FALL PRODUCTS
            [ // Eggplant Seeds
                'product_id' => $products['FAL-001'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '5',
            ],
            [
                'product_id' => $products['FAL-001'],
                'attr_name' => 'Regrow After Harvest?',
                'attr_value' => 'Yes',
            ],
            [ // Pumpkin Seeds
                'product_id' => $products['FAL-002'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '13',
            ],
            [ // Bok Choy Seeds
                'product_id' => $products['FAL-003'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '4',
            ],
            [ // Yam Seeds
                'product_id' => $products['FAL-004'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '10',
            ],
            [ // Cranberry Seeds
                'product_id' => $products['FAL-005'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '7',
            ],
            [
                'product_id' => $products['FAL-005'],
                'attr_name' => 'Regrow After Harvest?',
                'attr_value' => 'Yes',
            ],
            [ // Fairy Seeds
                'product_id' => $products['FAL-006'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '12',
            ],
            [ // Amaranth Seeds
                'product_id' => $products['FAL-007'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '7',
            ],
            [
                'product_id' => $products['FAL-007'],
                'attr_name' => 'Harvest Tool',
                'attr_value' => 'Scythe',
            ],
            [ // Grape Starter
                'product_id' => $products['FAL-008'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '10',
            ],
            [
                'product_id' => $products['FAL-008'],
                'attr_name' => 'Regrow After Harvest?',
                'attr_value' => 'Yes',
            ],
            [ // Artichoke Seeds
                'product_id' => $products['FAL-009'],
                'attr_name' => 'Grow Time (Days)',
                'attr_value' => '8',
            ],
        ]);
    }
}
