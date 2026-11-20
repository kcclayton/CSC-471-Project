<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeasonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('seasons')->insert([
            [
                'name' => 'Year-Round',
                'description' => 'Products available during every season.',
            ],
            [
                'name' => 'Spring',
                'description' => 'Products only available in the spring season.',
            ],
            [
                'name' => 'Summer',
                'description' => 'Products only available in the summer season.',
            ],
            [
                'name' => 'Fall',
                'description' => 'Products only available in the fall season.',
            ]
        ]);
    }
}
