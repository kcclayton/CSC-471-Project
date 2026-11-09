<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ProductAttribute>
 */
class ProductAttributeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'attr_name'  => fake()->randomElement(['Color', 'Size', 'Material']),
            'attr_value' => fake()->word(),
        ];
    }
}
