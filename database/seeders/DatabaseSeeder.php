<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@dsgallery.com',
            'password' => bcrypt('12345678'),
            'is_admin' => false,
        ]);

        User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'admin@dsgallery.com',
            'password' => bcrypt('12345678'),
            'is_admin' => true,
        ]);

        $this->call([
            ProductSeeder::class,
        ]);
    }
}
