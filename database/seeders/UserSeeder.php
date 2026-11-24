<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{

    public function run(): void
    {

        $pwrd = Hash::make('password');

        Storage::disk('public')->deleteDirectory('avatars');
        
        DB::table('users')->insert([
            // ADMINS
            [ // Pierre - Store Owner
                'email' => 'pierre@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Pierre',
                'last_name' => null,
                'is_admin' => true,
                'avatar_url' => $this->seedAvatar('pierre')
            ],
            [ // Caroline - Store Owner
                'email' => 'caroline@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Caroline',
                'last_name' => null,
                'is_admin' => true,
                'avatar_url' => $this->seedAvatar('caroline')
            ],
            [ // Sebastian - IT ✓
                'email' => 'sebastian@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Sebastian',
                'last_name' => null,
                'is_admin' => true,
                'avatar_url' => $this->seedAvatar('sebastian'),
            ],
            // BACHELORS
            [ // Alex ✓
                'email' => 'alex@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Alex',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('alex')
            ],
            [ // Elliot ✓
                'email' => 'elliot@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Elliot',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('elliot')
            ],
            [ // Shane ✓
                'email' => 'shane@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Shane',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('shane')
            ],
            // BACHELORETTES
            [ // Abigail ✓
                'email' => 'abigail@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Abigail',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('abigail'),
            ],
            [ // Emily ✓
                'email' => 'emily@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Emily',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('emily'),
            ],
            [ // Haley ✓
                'email' => 'haley@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Haley',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('haley'),
            ],
            [ // Leah ✓
                'email' => 'leah@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Leah',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('leah'),
            ],
            [ // Maru ✓
                'email' => 'maru@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Maru',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('maru'),
            ],
            [ // Penny ✓
                'email' => 'penny@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Penny',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('penny'),
            ],
            // MISC NPCs
            [ // Clint ✓
                'email' => 'clint@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Clint',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('clint'),
            ],
            [ // Demetrius ✓
                'email' => 'demetrius@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Demetrius',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('demetrius'),
            ],
            [ // Gus ✓
                'email' => 'gus@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Gus',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('gus'),
            ],
            [ // Jodi ✓
                'email' => 'jodi@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Jodi',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('jodi'),
            ],
            [ // Krobus ✓
                'email' => 'krobus@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Krobus',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('krobus'),
            ],
            [ // Lewis ✓
                'email' => 'mayorlewis@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Lewis',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('lewis'),
            ],
            [ // Marnie ✓
                'email' => 'marnie@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Marnie',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('marnie'),
            ],
            [ // Robin ✓
                'email' => 'robin@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Robin',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('robin'),
            ],
            [ // Sandy ✓
                'email' => 'sandy@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Sandy',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('sandy'),
            ],
            [ // Willy ✓
                'email' => 'willy@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Willy',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('willy'),
            ],
            [ // Rasmodius ✓
                'email' => 'thewizard@epelican.com',
                'pwrd_hash' => $pwrd,
                'first_name' => 'Magnus',
                'last_name' => null,
                'is_admin' => false,
                'avatar_url' => $this->seedAvatar('magnus'),
            ],
        ]);
    }

    /**
     * Copy a user's picture from database/seeders/images/avatars into public storage.
     * Returns the stored path for avatar_url, or null if there's no picture for this name.
     */
    private function seedAvatar(string $name): ?string
    {
        foreach (['png', 'jpg', 'jpeg', 'webp', 'gif'] as $extension) {
            $source = database_path("seeders/images/avatars/{$name}.{$extension}");

            if (file_exists($source)) {
                $path = "avatars/{$name}.{$extension}";
                Storage::disk('public')->put($path, file_get_contents($source));

                return $path;
            }
        }

        return null;
    }
}
