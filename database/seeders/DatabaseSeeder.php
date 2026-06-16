<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessImage;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ==========================================
        // 1. CREAR USUARIOS
        // ==========================================
        
        // Usuario administrador
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@berisso.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('admin123'),
                'is_admin' => true,
            ]
        );

        // Usuario de prueba (comerciante)
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Usuario Test',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]
        );

        // Usuario comerciante adicional
        $comercianteUser = User::firstOrCreate(
            ['email' => 'comerciante@berisso.com'],
            [
                'name' => 'Comerciante Local',
                'password' => bcrypt('comerciante123'),
                'is_admin' => false,
            ]
        );

        $this->command->info('✅ Usuarios creados:');
        $this->command->info('   👤 Admin: admin@berisso.com / admin123');
        $this->command->info('   👤 Test: test@example.com / password');
        $this->command->info('   👤 Comerciante: comerciante@berisso.com / comerciante123');

        // ==========================================
        // 2. CREAR CATEGORÍAS (TODAS ACTUALIZADAS)
        // ==========================================
        
        $categories = [
            // Nuevas categorías (orden deseado)
            ['name' => 'Ropa / Calzado', 'slug' => 'ropa-calzado'],
            ['name' => 'Panadería', 'slug' => 'panaderia'],
            ['name' => 'Confitería', 'slug' => 'confiteria'],
            ['name' => 'Kiosco', 'slug' => 'kiosco'],
            ['name' => 'Ferretería', 'slug' => 'ferreteria'],
            ['name' => 'Farmacia', 'slug' => 'farmacia'],
            ['name' => 'Verdulería / Frutería', 'slug' => 'verduleria-fruteria'],
            ['name' => 'Electrónica', 'slug' => 'electronica'],
            ['name' => 'Peluquería / Barbería', 'slug' => 'peluqueria-barberia'],
            ['name' => 'Restaurante / Cafetería', 'slug' => 'restaurante-cafeteria'],
            ['name' => 'Otro', 'slug' => 'otro'],
            
            // Categorías existentes
            ['name' => 'Almacén', 'slug' => 'almacen'],
            ['name' => 'Carnicería', 'slug' => 'carniceria'],
            ['name' => 'Comercio', 'slug' => 'comercio'],
            ['name' => 'Gastronomía', 'slug' => 'gastronomia'],
            ['name' => 'Salud', 'slug' => 'salud'],
            ['name' => 'Servicios', 'slug' => 'servicios'],
            ['name' => 'Turismo', 'slug' => 'turismo'],
            ['name' => 'Pescadería', 'slug' => 'pescaderia'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }

        $this->command->info('✅ Categorías creadas: ' . count($categories));

        // ==========================================
        // 3. FUNCIÓN AUXILIAR PARA CREAR NEGOCIOS
        // ==========================================
        
        $createBusiness = function($businessData, $user) {
            $category = Category::where('slug', $businessData['category_slug'])->first();
            $slug = Str::slug($businessData['name']);
            
            // Asegurar slug único
            $originalSlug = $slug;
            $counter = 1;
            while (Business::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            
            // Crear carpeta para el negocio
            $folderPath = "businesses/{$slug}";
            
            // Procesar imágenes si hay
            $imagePath = null;
            $galleryImages = [];
            
            // Si se proporciona una imagen principal en el seeder
            if (isset($businessData['image_url'])) {
                $imagePath = $businessData['image_url'];
            }
            
            // Crear el negocio
            $business = Business::create([
                'user_id' => $user->id,
                'name' => $businessData['name'],
                'slug' => $slug,
                'category_id' => $category?->id,
                'description' => $businessData['description'],
                'address' => $businessData['address'],
                'latitude' => $businessData['latitude'] ?? null,
                'longitude' => $businessData['longitude'] ?? null,
                'phone' => $businessData['phone'],
                'website' => $businessData['website'] ?? null,
                'hours' => $businessData['hours'] ?? null,
                'email_lugar' => $businessData['email_lugar'] ?? null,
                'facebook' => $businessData['facebook'] ?? null,
                'instagram' => $businessData['instagram'] ?? null,
                'image' => $imagePath,
                'status' => $businessData['status'] ?? 'approved',
                'published' => $businessData['status'] === 'approved' ? true : false,
                'approved_at' => $businessData['status'] === 'approved' ? now() : null,
                'approved_by' => $businessData['status'] === 'approved' ? $user->id : null,
            ]);
            
            // Agregar imágenes de galería si hay
            if (isset($businessData['gallery']) && is_array($businessData['gallery'])) {
                foreach ($businessData['gallery'] as $index => $imgUrl) {
                    BusinessImage::create([
                        'business_id' => $business->id,
                        'image_url' => $imgUrl,
                        'order' => $index,
                    ]);
                }
            }
            
            return $business;
        };

        // ==========================================
        // 4. NEGOCIOS APROBADOS (VISIBLES EN EL INDEX)
        // ==========================================

        $this->command->info('📝 Creando negocios aprobados...');

        // Negocio 1: Almacén La Esquina
        $createBusiness([
            'name' => 'Almacén La Esquina',
            'category_slug' => 'almacen',
            'description' => 'Frutas y verduras frescas todos los días, traídas directamente del Mercado Central. También contamos con fiambrería artesanal, quesos, productos de almacén y conservas. Pedidos a domicilio en Berisso sin cargo mínimo.',
            'address' => 'Calle 7 y 22, Berisso',
            'latitude' => -34.8731,
            'longitude' => -57.8867,
            'phone' => '2214890123',
            'hours' => 'Lun–Sáb 7:30–13:00 y 16:00–19:30',
            'email_lugar' => 'laesquina.berisso@gmail.com',
            'facebook' => 'https://facebook.com/almacenlaesquina',
            'instagram' => 'https://instagram.com/almacenlaesquina',
            'image_url' => 'https://picsum.photos/id/1/800/600',
            'status' => 'approved',
            'gallery' => [
                'https://picsum.photos/id/13/800/600',
                'https://picsum.photos/id/102/800/600',
                'https://picsum.photos/id/107/800/600',
                'https://picsum.photos/id/30/800/600',
            ]
        ], $adminUser);

        // Negocio 2: Panadería La Familia
        $createBusiness([
            'name' => 'Panadería La Familia',
            'category_slug' => 'panaderia',
            'description' => 'Pan artesanal, facturas, tortas y masas finas. Todo hecho con amor y los mejores ingredientes. Probá nuestras medialunas de manteca y pan casero.',
            'address' => 'Calle 12 y 65, Berisso',
            'latitude' => -34.8750,
            'longitude' => -57.8850,
            'phone' => '2214778899',
            'hours' => 'Lun a Dom 6:00–21:00',
            'email_lugar' => 'panaderiafamilia@gmail.com',
            'facebook' => 'https://facebook.com/panaderiafamilia',
            'instagram' => 'https://instagram.com/panaderiafamilia',
            'image_url' => 'https://picsum.photos/id/112/800/600',
            'status' => 'approved',
            'gallery' => [
                'https://picsum.photos/id/123/800/600',
                'https://picsum.photos/id/111/800/600',
                'https://picsum.photos/id/105/800/600',
            ]
        ], $adminUser);

        // Negocio 3: Verdulería El Jardín
        $createBusiness([
            'name' => 'Verdulería El Jardín',
            'category_slug' => 'verduleria-fruteria',
            'description' => 'Productos orgánicos y de estación. Trabajamos directamente con productores de la zona para ofrecerte la mejor calidad. Tenemos frutas, verduras, legumbres y hierbas aromáticas.',
            'address' => 'Calle 8 y 63, Berisso',
            'latitude' => -34.8720,
            'longitude' => -57.8880,
            'phone' => '2214567890',
            'hours' => 'Lun a Sab 8:00–20:00, Dom 9:00–13:00',
            'email_lugar' => 'eljardin@gmail.com',
            'website' => 'https://eljardin.com.ar',
            'instagram' => 'https://instagram.com/verduleriaeljardin',
            'image_url' => 'https://picsum.photos/id/118/800/600',
            'status' => 'approved',
            'gallery' => [
                'https://picsum.photos/id/110/800/600',
                'https://picsum.photos/id/14/800/600',
                'https://picsum.photos/id/102/800/600',
            ]
        ], $testUser);

        // Negocio 4: Farmacia del Pueblo
        $createBusiness([
            'name' => 'Farmacia del Pueblo',
            'category_slug' => 'farmacia',
            'description' => 'Farmacia con servicio de asesoramiento de farmacéutico. Ofrecemos medicamentos, productos de cuidado personal y cosméticos. Atención personalizada y profesional.',
            'address' => 'Calle 8 N° 4100, Berisso',
            'latitude' => -34.8745,
            'longitude' => -57.8875,
            'phone' => '0221-789-0123',
            'hours' => 'Lunes a Viernes: 8:00 - 20:00, Sábado: 9:00 - 18:00',
            'email_lugar' => 'farmaciapueblo@gmail.com',
            'facebook' => 'https://facebook.com/farmaciapueblo',
            'instagram' => 'https://instagram.com/farmaciapueblo',
            'image_url' => 'https://picsum.photos/id/49/800/600',
            'status' => 'approved',
            'gallery' => [
                'https://picsum.photos/id/48/800/601',
                'https://picsum.photos/id/49/800/602',
            ]
        ], $comercianteUser);

        // Negocio 5: Restaurante La Costanera
        $createBusiness([
            'name' => 'Restaurante La Costanera',
            'category_slug' => 'restaurante-cafeteria',
            'description' => 'Restaurante especializado en comida marina y platos típicos locales. Con vista al río, el mejor lugar para disfrutar en familia.',
            'address' => 'Av. 1 de Mayo 1500, Berisso',
            'latitude' => -34.8710,
            'longitude' => -57.8890,
            'phone' => '0221-123-4567',
            'hours' => 'Lunes a Domingo: 12:00 - 23:00',
            'email_lugar' => 'lacostanera@gmail.com',
            'facebook' => 'https://facebook.com/lacostanera',
            'instagram' => 'https://instagram.com/lacostanera',
            'image_url' => 'https://picsum.photos/id/106/800/600',
            'status' => 'approved',
            'gallery' => [
                'https://picsum.photos/id/106/800/601',
                'https://picsum.photos/id/106/800/602',
                'https://picsum.photos/id/106/800/603',
            ]
        ], $adminUser);

        // Negocio 6: Pescadería El Puerto
        $createBusiness([
            'name' => 'Pescadería El Puerto',
            'category_slug' => 'pescaderia',
            'description' => 'Pescados y mariscos frescos del Río de la Plata. Entregas a domicilio en Berisso y Ensenada. Pescado del día, langostinos, calamares y más.',
            'address' => 'Calle 1 y 53, Berisso',
            'latitude' => -34.8760,
            'longitude' => -57.8840,
            'phone' => '2214332211',
            'hours' => 'Mar a Dom 9:00–18:00',
            'email_lugar' => 'elpuerto.pesca@gmail.com',
            'facebook' => 'https://facebook.com/pescaderiaelpuerto',
            'instagram' => 'https://instagram.com/pescaderiaelpuerto',
            'image_url' => 'https://picsum.photos/id/31/800/600',
            'status' => 'approved',
            'gallery' => [
                'https://picsum.photos/id/119/800/600',
                'https://picsum.photos/id/32/800/600',
            ]
        ], $comercianteUser);

        // Negocio 7: Peluquería Estilo
        $createBusiness([
            'name' => 'Peluquería Estilo',
            'category_slug' => 'peluqueria-barberia',
            'description' => 'Peluquería y barbería con profesionales capacitados. Cortes de cabello, peinados, barba y tratamientos capilares. Atención con cita previa.',
            'address' => 'Calle 122 N° 3500, Berisso',
            'latitude' => -34.8735,
            'longitude' => -57.8860,
            'phone' => '0221-567-8901',
            'hours' => 'Lunes a Viernes: 9:00 - 20:00, Sábado: 9:00 - 14:00',
            'email_lugar' => 'peluqueriaestilo@gmail.com',
            'facebook' => 'https://facebook.com/peluqueriaestilo',
            'instagram' => 'https://instagram.com/peluqueriaestilo',
            'image_url' => 'https://picsum.photos/id/111/800/600',
            'status' => 'approved',
            'gallery' => [
                'https://picsum.photos/id/111/800/601',
                'https://picsum.photos/id/111/800/602',
            ]
        ], $testUser);

        // ==========================================
        // 5. NEGOCIOS PENDIENTES (PARA QUE EL ADMIN APRUEBE)
        // ==========================================

        $this->command->info('📝 Creando negocios pendientes...');

        // Negocio pendiente 1: Nueva Ferretería
        $createBusiness([
            'name' => 'Ferretería El Tornillo',
            'category_slug' => 'ferreteria',
            'description' => 'Ferretería con todo lo que necesitás para tu hogar. Herramientas, materiales de construcción, pinturas, electricidad, plomería y más.',
            'address' => 'Calle 120 N° 3000, Berisso',
            'latitude' => -34.8725,
            'longitude' => -57.8870,
            'phone' => '0221-345-6789',
            'hours' => 'Lunes a Sábado: 8:00 - 19:00',
            'email_lugar' => 'ferreteriaeltornillo@gmail.com',
            'facebook' => 'https://facebook.com/ferreteriaeltornillo',
            'instagram' => 'https://instagram.com/ferreteriaeltornillo',
            'image_url' => 'https://picsum.photos/id/20/800/600',
            'status' => 'pending',
            'gallery' => [
                'https://picsum.photos/id/20/800/601',
                'https://picsum.photos/id/20/800/602',
            ]
        ], $testUser);

        // Negocio pendiente 2: Confitería Dulce Sabor
        $createBusiness([
            'name' => 'Confitería Dulce Sabor',
            'category_slug' => 'confiteria',
            'description' => 'Confitería con las mejores tortas, pastelería y café. Ideal para merendar o desayunar. Productos artesanales elaborados diariamente.',
            'address' => 'Calle 121 N° 4200, Berisso',
            'latitude' => -34.8740,
            'longitude' => -57.8855,
            'phone' => '0221-456-7890',
            'hours' => 'Martes a Domingo: 8:00 - 20:00',
            'email_lugar' => 'dulcesabor@gmail.com',
            'instagram' => 'https://instagram.com/confiteriadulcesabor',
            'image_url' => 'https://picsum.photos/id/112/800/600',
            'status' => 'pending',
            'gallery' => [
                'https://picsum.photos/id/112/800/601',
                'https://picsum.photos/id/112/800/602',
            ]
        ], $comercianteUser);

        // Negocio pendiente 3: Ropa y Calzado Moderno
        $createBusiness([
            'name' => 'Ropa y Calzado Moderno',
            'category_slug' => 'ropa-calzado',
            'description' => 'Tienda de indumentaria y calzado para toda la familia. Últimas tendencias, marcas reconocidas y precios accesibles.',
            'address' => 'Calle 123 N° 2800, Berisso',
            'latitude' => -34.8738,
            'longitude' => -57.8865,
            'phone' => '0221-567-8901',
            'hours' => 'Lunes a Sábado: 9:00 - 20:00',
            'email_lugar' => 'ropamoderna@gmail.com',
            'facebook' => 'https://facebook.com/ropamoderna',
            'instagram' => 'https://instagram.com/ropamoderna',
            'image_url' => 'https://picsum.photos/id/15/800/600',
            'status' => 'pending',
        ], $testUser);

        // ==========================================
        // 6. RESUMEN FINAL
        // ==========================================

        $totalBusinesses = Business::count();
        $approvedBusinesses = Business::where('status', 'approved')->count();
        $pendingBusinesses = Business::where('status', 'pending')->count();
        $totalImages = BusinessImage::count();

        $this->command->info('✅ Seeding completado exitosamente!');
        $this->command->info('📊 Resumen:');
        $this->command->info("   📦 Total de negocios: {$totalBusinesses}");
        $this->command->info("   ✅ Aprobados: {$approvedBusinesses}");
        $this->command->info("   ⏳ Pendientes: {$pendingBusinesses}");
        $this->command->info("   🖼️ Imágenes totales: {$totalImages}");
        $this->command->info('');
        $this->command->info('👤 Credenciales de acceso:');
        $this->command->info('   🔑 Admin: admin@berisso.com / admin123');
        $this->command->info('   🔑 Test: test@example.com / password');
        $this->command->info('   🔑 Comerciante: comerciante@berisso.com / comerciante123');
    }
}