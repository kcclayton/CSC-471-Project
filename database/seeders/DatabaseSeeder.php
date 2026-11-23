<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SeasonSeeder::class,
            ProductSeeder::class,
            ProductAttributesSeeder::class,
            OrderSeeder::class,
            CartSeeder::class,
        ]);
    }
}
