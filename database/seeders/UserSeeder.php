<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {

        $pwrd = Hash::make('password');
        
        DB::table('users')->insert([
            [
                'email' => 'pierre@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Pierre',
                'last_name' => null,
                'is_admin' => true,
            ],
            [
                'email' => 'sebastian@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Sebastian',
                'last_name' => null,
                'is_admin' => true,
            ],
            [
                'email' => 'abigail@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Abigail',
                'last_name' => null,
                'is_admin' => false,
            ],
        ]);
    }
}
