<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessImage;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ==========================================
        // 1. CREAR USUARIO DE PRUEBA
        // ==========================================
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@berisso.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        // ==========================================
        // 2. CREAR CATEGORÍAS
        // ==========================================
        $categories = [
            ['name' => 'Verdulería / Frutería', 'slug' => 'verduleria-fruteria'],
            ['name' => 'Almacén', 'slug' => 'almacen'],
            ['name' => 'Panadería', 'slug' => 'panaderia'],
            ['name' => 'Carnicería', 'slug' => 'carniceria'],
            ['name' => 'Farmacia', 'slug' => 'farmacia'],
            ['name' => 'Kiosco', 'slug' => 'kiosco'],
            ['name' => 'Restaurante', 'slug' => 'restaurante'],
            ['name' => 'Pescadería', 'slug' => 'pescaderia'],
            ['name' => 'Gastronomía', 'slug' => 'gastronomia'],
            ['name' => 'Salud', 'slug' => 'salud'],
            ['name' => 'Comercio', 'slug' => 'comercio'],
            ['name' => 'Servicios', 'slug' => 'servicios'],
            ['name' => 'Turismo', 'slug' => 'turismo'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }

        // ==========================================
        // 3. FUNCIÓN AUXILIAR PARA CREAR NEGOCIO CON GALERÍA
        // ==========================================
        $createBusinessWithGallery = function($businessData, $user, $extraImages = []) {
            $category = Category::where('slug', $businessData['category_slug'])->first();
            $slug = Str::slug($businessData['name']);
            
            $originalSlug = $slug;
            $counter = 1;
            while (Business::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            
            $business = Business::create([
                'user_id' => $user->id,
                'name' => $businessData['name'],
                'slug' => $slug,
                'category_id' => $category?->id,
                'description' => $businessData['description'],
                'address' => $businessData['address'],
                'phone' => $businessData['phone'],
                'website' => $businessData['website'] ?? null,
                'hours' => $businessData['hours'],
                'image' => $businessData['image'],
                'email_lugar' => $businessData['email_lugar'],
                'facebook' => $businessData['facebook'] ?? null,
                'instagram' => $businessData['instagram'] ?? null,
                'published' => true,
            ]);
            
            // Agregar imágenes extras
            foreach ($extraImages as $index => $imgUrl) {
                BusinessImage::create([
                    'business_id' => $business->id,
                    'image_url' => $imgUrl,
                    'order' => $index,
                ]);
            }
            
            return $business;
        };

        // ==========================================
        // 4. TODOS LOS NEGOCIOS CON IMÁGENES EXTRAS
        // ==========================================

        // Negocio 1: Restaurante La Costanera
        $createBusinessWithGallery(
            [
                'name' => 'Restaurante La Costanera',
                'category_slug' => 'gastronomia',
                'description' => 'Restaurante especializado en comida marina y platos típicos locales.',
                'address' => 'Av. 1 de Mayo 1500, Berisso',
                'phone' => '0221-123-4567',
                'website' => 'https://lacostanera.example.com',
                'hours' => 'Lunes a Domingo: 12:00 - 23:00',
                'image' => 'https://picsum.photos/id/106/800/600',
                'email_lugar' => 'lacostanera@gmail.com',
                'facebook' => 'https://facebook.com/lacostanera',
                'instagram' => 'https://instagram.com/lacostanera',
            ],
            $user,
            [
                'https://picsum.photos/id/106/800/601',
                'https://picsum.photos/id/106/800/602',
                'https://picsum.photos/id/106/800/603',
            ]
        );

        // Negocio 2: Clínica Berisso
        $createBusinessWithGallery(
            [
                'name' => 'Clínica Berisso',
                'category_slug' => 'salud',
                'description' => 'Centro médico integral con servicios de urgencia y atención general.',
                'address' => 'Calle 121 N° 4200, Berisso',
                'phone' => '0221-445-6789',
                'website' => 'https://clinicaberisso.example.com',
                'hours' => 'Lunes a Viernes: 8:00 - 18:00',
                'image' => 'https://picsum.photos/id/48/800/600',
                'email_lugar' => 'clinica@berisso.com',
                'facebook' => null,
                'instagram' => 'https://instagram.com/clinicaberisso',
            ],
            $user,
            [
                'https://picsum.photos/id/48/800/601',
                'https://picsum.photos/id/48/800/602',
                'https://picsum.photos/id/48/800/603',
            ]
        );

        // Negocio 3: Supermercado Central
        $createBusinessWithGallery(
            [
                'name' => 'Supermercado Central',
                'category_slug' => 'comercio',
                'description' => 'Supermercado con amplia variedad de productos y oferta de marcas.',
                'address' => 'Calle 122 N° 3500, Berisso',
                'phone' => '0221-234-5678',
                'website' => 'https://supercentral.example.com',
                'hours' => 'Lunes a Sábado: 8:00 - 20:00, Domingo: 9:00 - 19:00',
                'image' => 'https://picsum.photos/id/20/800/600',
                'email_lugar' => 'supercentral@gmail.com',
                'facebook' => 'https://facebook.com/supercentral',
                'instagram' => null,
            ],
            $user,
            [
                'https://picsum.photos/id/20/800/601',
                'https://picsum.photos/id/20/800/602',
                'https://picsum.photos/id/20/800/603',
                'https://picsum.photos/id/20/800/604',
            ]
        );

        // Negocio 4: Taller Mecánico Rossi
        $createBusinessWithGallery(
            [
                'name' => 'Taller Mecánico Rossi',
                'category_slug' => 'servicios',
                'description' => 'Reparación y mantenimiento de vehículos con personal certificado.',
                'address' => 'Av. 9 de Julio 2100, Berisso',
                'phone' => '0221-567-8901',
                'website' => 'https://tallerrossi.example.com',
                'hours' => 'Lunes a Viernes: 8:30 - 17:30, Sábado: 8:30 - 13:00',
                'image' => 'https://picsum.photos/id/111/800/600',
                'email_lugar' => 'tallerrossi@gmail.com',
                'facebook' => 'https://facebook.com/tallerrossi',
                'instagram' => 'https://instagram.com/tallerrossi',
            ],
            $user,
            [
                'https://picsum.photos/id/111/800/601',
                'https://picsum.photos/id/111/800/602',
            ]
        );

        // Negocio 5: Hostel Boutique Berisso
        $createBusinessWithGallery(
            [
                'name' => 'Hostel Boutique Berisso',
                'category_slug' => 'turismo',
                'description' => 'Alojamiento cómodo y moderno para turistas y viajeros.',
                'address' => 'Calle 123 N° 2800, Berisso',
                'phone' => '0221-678-9012',
                'website' => 'https://hostelboutique.example.com',
                'hours' => 'Disponible 24/7',
                'image' => 'https://picsum.photos/id/15/800/600',
                'email_lugar' => 'hostelboutique@gmail.com',
                'facebook' => null,
                'instagram' => null,
            ],
            $user,
            [
                'https://picsum.photos/id/15/800/601',
                'https://picsum.photos/id/15/800/602',
                'https://picsum.photos/id/15/800/603',
                'https://picsum.photos/id/15/800/604',
                'https://picsum.photos/id/15/800/605',
            ]
        );

        // Negocio 6: Farmacia del Pueblo
        $createBusinessWithGallery(
            [
                'name' => 'Farmacia del Pueblo',
                'category_slug' => 'salud',
                'description' => 'Farmacia con servicio de asesoramiento de farmacéutico.',
                'address' => 'Calle 8 N° 4100, Berisso',
                'phone' => '0221-789-0123',
                'website' => 'https://farmaciapueblo.example.com',
                'hours' => 'Lunes a Viernes: 8:00 - 20:00, Sábado: 9:00 - 18:00',
                'image' => 'https://picsum.photos/id/49/800/600',
                'email_lugar' => 'farmaciapueblo@gmail.com',
                'facebook' => 'https://facebook.com/farmaciapueblo',
                'instagram' => 'https://instagram.com/farmaciapueblo',
            ],
            $user,
            [
                'https://picsum.photos/id/49/800/601',
                'https://picsum.photos/id/49/800/602',
            ]
        );

        // Negocio 7: Pizzería Don Juan
        $createBusinessWithGallery(
            [
                'name' => 'Pizzería Don Juan',
                'category_slug' => 'gastronomia',
                'description' => 'Pizzería tradicional con recetas caseras y ingredientes frescos.',
                'address' => 'Calle 120 N° 3000, Berisso',
                'phone' => '0221-012-3456',
                'website' => 'https://pizzeriadonjuan.example.com',
                'hours' => 'Martes a Domingo: 17:00 - 23:30',
                'image' => 'https://picsum.photos/id/108/800/600',
                'email_lugar' => 'donjuan@gmail.com',
                'facebook' => 'https://facebook.com/pizzeriadonjuan',
                'instagram' => 'https://instagram.com/pizzeriadonjuan',
            ],
            $user,
            [
                'https://picsum.photos/id/108/800/601',
                'https://picsum.photos/id/108/800/602',
                'https://picsum.photos/id/108/800/603',
            ]
        );

        // Negocio 8: Almacén La Esquina (con adminUser)
        $business1 = Business::firstOrCreate(
            ['slug' => 'almacen-la-esquina'],
            [
                'name' => 'Almacén La Esquina',
                'category_id' => Category::where('slug', 'almacen')->first()->id,
                'description' => 'Frutas y verduras frescas todos los días, traídas directamente del Mercado Central. También contamos con fiambrería artesanal, quesos, productos de almacén y conservas. Pedidos a domicilio en Berisso sin cargo mínimo.',
                'address' => 'Calle 7 y 22, Berisso',
                'hours' => 'Lun–Sáb 7:30–13:00 y 16:00–19:30',
                'phone' => '2214890123',
                'website' => null,
                'email_lugar' => 'laesquina.berisso@gmail.com',
                'image' => 'https://picsum.photos/id/1/800/600',
                'facebook' => 'https://facebook.com/almacenlaesquina',
                'instagram' => 'https://instagram.com/almacenlaesquina',
                'user_id' => $adminUser->id,
                'published' => true,
            ]
        );

        $imagenesAlmacen = [
            'https://picsum.photos/id/1/800/600',
            'https://picsum.photos/id/13/800/600',
            'https://picsum.photos/id/102/800/600',
            'https://picsum.photos/id/107/800/600',
            'https://picsum.photos/id/30/800/600',
        ];

        foreach ($imagenesAlmacen as $index => $imgUrl) {
            BusinessImage::firstOrCreate(
                [
                    'business_id' => $business1->id,
                    'image_url' => $imgUrl,
                ],
                ['order' => $index]
            );
        }

        // Negocio 9: Verdulería El Jardín
        $business2 = Business::firstOrCreate(
            ['slug' => 'verduleria-el-jardin'],
            [
                'name' => 'Verdulería El Jardín',
                'category_id' => Category::where('slug', 'verduleria-fruteria')->first()->id,
                'description' => 'Productos orgánicos y de estación. Trabajamos directamente con productores de la zona para ofrecerte la mejor calidad. Tenemos frutas, verduras, legumbres y hierbas aromáticas.',
                'address' => 'Calle 8 y 63, Berisso',
                'hours' => 'Lun a Sab 8:00–20:00, Dom 9:00–13:00',
                'phone' => '2214567890',
                'website' => 'https://eljardin.com.ar',
                'email_lugar' => 'eljardin@gmail.com',
                'image' => 'https://picsum.photos/id/118/800/600',
                'facebook' => null,
                'instagram' => 'https://instagram.com/verduleriaeljardin',
                'user_id' => $adminUser->id,
                'published' => true,
            ]
        );

        $imagenesVerduleria = [
            'https://picsum.photos/id/118/800/600',
            'https://picsum.photos/id/110/800/600',
            'https://picsum.photos/id/14/800/600',
            'https://picsum.photos/id/102/800/600',
        ];

        foreach ($imagenesVerduleria as $index => $imgUrl) {
            BusinessImage::firstOrCreate(
                [
                    'business_id' => $business2->id,
                    'image_url' => $imgUrl,
                ],
                ['order' => $index]
            );
        }

        // Negocio 10: Panadería La Familia
        $business3 = Business::firstOrCreate(
            ['slug' => 'panaderia-la-familia'],
            [
                'name' => 'Panadería La Familia',
                'category_id' => Category::where('slug', 'panaderia')->first()->id,
                'description' => 'Pan artesanal, facturas, tortas y masas finas. Todo hecho con amor y los mejores ingredientes. Probá nuestras medialunas de manteca y pan casero.',
                'address' => 'Calle 12 y 65, Berisso',
                'hours' => 'Lun a Dom 6:00–21:00',
                'phone' => '2214778899',
                'website' => null,
                'email_lugar' => 'panaderiafamilia@gmail.com',
                'image' => 'https://picsum.photos/id/112/800/600',
                'facebook' => 'https://facebook.com/panaderiafamilia',
                'instagram' => 'https://instagram.com/panaderiafamilia',
                'user_id' => $adminUser->id,
                'published' => true,
            ]
        );

        $imagenesPanaderia = [
            'https://picsum.photos/id/112/800/600',
            'https://picsum.photos/id/123/800/600',
            'https://picsum.photos/id/111/800/600',
            'https://picsum.photos/id/105/800/600',
        ];

        foreach ($imagenesPanaderia as $index => $imgUrl) {
            BusinessImage::firstOrCreate(
                [
                    'business_id' => $business3->id,
                    'image_url' => $imgUrl,
                ],
                ['order' => $index]
            );
        }

        // Negocio 11: Pescadería El Puerto
        $business4 = Business::firstOrCreate(
            ['slug' => 'pescaderia-el-puerto'],
            [
                'name' => 'Pescadería El Puerto',
                'category_id' => Category::where('slug', 'pescaderia')->first()->id,
                'description' => 'Pescados y mariscos frescos del Río de la Plata. Entregas a domicilio en Berisso y Ensenada. Pescado del día, langostinos, calamares y más.',
                'address' => 'Calle 1 y 53, Berisso',
                'hours' => 'Mar a Dom 9:00–18:00',
                'phone' => '2214332211',
                'website' => null,
                'email_lugar' => 'elpuerto.pesca@gmail.com',
                'image' => 'https://picsum.photos/id/31/800/600',
                'facebook' => 'https://facebook.com/pescaderiaelpuerto',
                'instagram' => 'https://instagram.com/pescaderiaelpuerto',
                'user_id' => $adminUser->id,
                'published' => true,
            ]
        );

        $imagenesPescaderia = [
            'https://picsum.photos/id/31/800/600',
            'https://picsum.photos/id/119/800/600',
            'https://picsum.photos/id/32/800/600',
        ];

        foreach ($imagenesPescaderia as $index => $imgUrl) {
            BusinessImage::firstOrCreate(
                [
                    'business_id' => $business4->id,
                    'image_url' => $imgUrl,
                ],
                ['order' => $index]
            );
        }

        $this->command->info('✅ Seeding completado exitosamente!');
        $this->command->info('📊 Negocios creados: ' . Business::count());
        $this->command->info('🖼️ Imágenes cargadas: ' . BusinessImage::count());
        $this->command->info('👤 Usuario test: test@example.com / password');
        $this->command->info('👤 Usuario admin: admin@berisso.com / password');
    }
}