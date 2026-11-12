<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('users')->insert([
            [
            'email' => 'pierre@epelican.com',
            'pwrd_hash' => Hash::make('password'),
            'first_name' => 'Pierre',
            'last_name' => 'Marchand',
            'is_admin' => true,
        ],
        [
            'email' => 'caroline@epelican.com',
            'pwrd_hash' => Hash::make('password'),
            'first_name' => 'Caroline',
            'last_name' => 'Marchand',
            'is_admin' => false,
        ],
        [
            'email' => 'abigail@epelican.com',
            'pwrd_hash' => Hash::make('password'),
            'first_name' => 'Abigail',
            'last_name' => 'Marchand',
            'is_admin' => false,
        ]
        ]);
    }
}
