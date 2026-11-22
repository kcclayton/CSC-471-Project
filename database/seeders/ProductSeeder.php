<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // SELECT season_id, name FROM seasons
        // gives ['Year-Round' => 1, 'Spring' => 2, 'Summer' => 3, 'Fall' => 4]
        $seasons = DB::table('seasons')->pluck('season_id', 'name');

        $now = now();

        // Start with an empty products folder so old pictures don't pile up
        Storage::disk('public')->deleteDirectory('products');

        DB::table('products')->insert([
            [
                'sku' => 'ALL-001',
                'name' => 'Grass Starter',
                'description' => 'Place this on your farm to start a new patch of grass.',
                'price' => 100,
                'image_url' => $this->seedImage('ALL-001'),
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
                'image_url' => $this->seedImage('SPR-001'),
                'season_id' => $seasons['Spring'],
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
                'image_url' => $this->seedImage('SUM-001'),
                'season_id' => $seasons['Summer'],
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
                'image_url' => $this->seedImage('FAL-001'),
                'season_id' => $seasons['Fall'],
                'stock_qty' => 100,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Copy a product's picture from database/seeders/images into public storage.
     * Returns the stored path for image_url, or null if there's no picture for this SKU.
     */
    private function seedImage(string $sku): ?string
    {
        foreach (['png', 'jpg', 'jpeg', 'webp'] as $extension) {
            $source = database_path("seeders/images/{$sku}.{$extension}");

            if (file_exists($source)) {
                $path = 'products/'.strtolower($sku).'.'.$extension;
                Storage::disk('public')->put($path, file_get_contents($source));

                return $path;
            }
        }

        return null;
    }
}
