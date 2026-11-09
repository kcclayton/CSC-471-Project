<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Log in with test@example.com / password
        User::factory()->admin()->create([
            'email'      => 'test@example.com',
            'first_name' => 'Test',
            'last_name'  => 'User',
        ]);

        // 4 categories, each with 5 products, each product with 2 attributes
        Category::factory(4)
            ->has(
                Product::factory(5)->has(ProductAttribute::factory(2), 'productAttributes'),
                'products'
            )
            ->create();
    }
}
