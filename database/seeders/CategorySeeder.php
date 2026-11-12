<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Spring',
                'description' => 'Crops that grow in the spring season',
            ],
            [
                'name' => 'Summer',
                'description' => 'Crops that grow in the summer season',
            ],
            [
                'name' => 'Fall',
                'description' => 'Crops that grow in the fall season',
            ]
        ]);
    }
}
