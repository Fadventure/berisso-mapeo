<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

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

        $businesses = [
            [
                'name' => 'Restaurante La Costanera',
                'category' => 'gastronomia',
                'description' => 'Restaurante especializado en comida marina y platos típicos locales.',
                'address' => 'Av. 1 de Mayo 1500, Berisso',
                'phone' => '0221-123-4567',
                'website' => 'https://lacostanera.example.com',
                'hours' => 'Lunes a Domingo: 12:00 - 23:00',
                'image' => 'https://picsum.photos/seed/rest1/800/600',
            ],
            [
                'name' => 'Clínica Berisso',
                'category' => 'salud',
                'description' => 'Centro médico integral con servicios de urgencia y atención general.',
                'address' => 'Calle 121 N° 4200, Berisso',
                'phone' => '0221-445-6789',
                'website' => 'https://clinicaberisso.example.com',
                'hours' => 'Lunes a Viernes: 8:00 - 18:00',
                'image' => 'https://picsum.photos/seed/clinic1/800/600',
            ],
            [
                'name' => 'Supermercado Central',
                'category' => 'comercio',
                'description' => 'Supermercado con amplia variedad de productos y oferta de marcas.',
                'address' => 'Calle 122 N° 3500, Berisso',
                'phone' => '0221-234-5678',
                'website' => 'https://supercentral.example.com',
                'hours' => 'Lunes a Sábado: 8:00 - 20:00, Domingo: 9:00 - 19:00',
                'image' => 'https://picsum.photos/seed/super1/800/600',
            ],
            [
                'name' => 'Taller Mecánico Rossi',
                'category' => 'servicios',
                'description' => 'Reparación y mantenimiento de vehículos con personal certificado.',
                'address' => 'Av. 9 de Julio 2100, Berisso',
                'phone' => '0221-567-8901',
                'website' => 'https://tallerrossi.example.com',
                'hours' => 'Lunes a Viernes: 8:30 - 17:30, Sábado: 8:30 - 13:00',
                'image' => 'https://picsum.photos/seed/taller1/800/600',
            ],
            [
                'name' => 'Hostel Boutique Berisso',
                'category' => 'turismo',
                'description' => 'Alojamiento cómodo y moderno para turistas y viajeros.',
                'address' => 'Calle 123 N° 2800, Berisso',
                'phone' => '0221-678-9012',
                'website' => 'https://hostelboutique.example.com',
                'hours' => 'Disponible 24/7',
                'image' => 'https://picsum.photos/seed/hostel1/800/600',
            ],
            [
                'name' => 'Farmacia del Pueblo',
                'category' => 'salud',
                'description' => 'Farmacia con servicio de asesoramiento de farmacéutico.',
                'address' => 'Calle 8 N° 4100, Berisso',
                'phone' => '0221-789-0123',
                'website' => 'https://farmaciapueblo.example.com',
                'hours' => 'Lunes a Viernes: 8:00 - 20:00, Sábado: 9:00 - 18:00',
                'image' => 'https://picsum.photos/seed/farm1/800/600',
            ],
            [
                'name' => 'Pizzería Don Juan',
                'category' => 'gastronomia',
                'description' => 'Pizzería tradicional con recetas caseras y ingredientes frescos.',
                'address' => 'Calle 120 N° 3000, Berisso',
                'phone' => '0221-012-3456',
                'website' => 'https://pizzeriadonjuan.example.com',
                'hours' => 'Martes a Domingo: 17:00 - 23:30',
                'image' => 'https://picsum.photos/seed/pizza1/800/600',
            ],
        ];

        foreach ($businesses as $businessData) {
            $categorySlug = $businessData['category'];
            $category = Category::where('slug', $categorySlug)->first();

            $slug = Str::slug($businessData['name']);

            // Ensure uniqueness by appending random 4 chars if needed
            while (Business::where('slug', $slug)->exists()) {
                $slug = Str::slug($businessData['name']) . '-' . Str::random(4);
            }

            Business::firstOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $user->id,
                    'name' => $businessData['name'],
                    'category_id' => $category?->id,
                    'description' => $businessData['description'],
                    'address' => $businessData['address'],
                    'phone' => $businessData['phone'],
                    'website' => $businessData['website'],
                    'hours' => $businessData['hours'],
                    'image' => $businessData['image'],
                    'published' => true,
                ]
            );
        }
        }
    }
