<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku'         => fake()->unique()->bothify('SKU-#####'),
            'name'        => ucwords(fake()->words(3, true)),
            'description' => fake()->paragraph(),
            'price'       => fake()->randomFloat(2, 5, 150),
            'image_url'   => null,
            'category_id' => Category::factory(),
            'stock_qty'   => fake()->numberBetween(0, 50),
            'is_active'   => true,
        ];
    }
}
