<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = [
            ['name' => 'Gastronomía', 'slug' => 'gastronomia'],
            ['name' => 'Salud', 'slug' => 'salud'],
            ['name' => 'Comercio', 'slug' => 'comercio'],
            ['name' => 'Servicios', 'slug' => 'servicios'],
            ['name' => 'Turismo', 'slug' => 'turismo'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], ['name' => $category['name']]);
        }
    }
}
