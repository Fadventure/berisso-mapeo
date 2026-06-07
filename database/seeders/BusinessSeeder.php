<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessImage;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurarnos de que existe un usuario para asociar
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@berisso.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Crear categorías si no existen
        $categories = [
            ['name' => 'Verdulería / Frutería', 'slug' => 'verduleria-fruteria'],
            ['name' => 'Almacén', 'slug' => 'almacen'],
            ['name' => 'Panadería', 'slug' => 'panaderia'],
            ['name' => 'Carnicería', 'slug' => 'carniceria'],
            ['name' => 'Farmacia', 'slug' => 'farmacia'],
            ['name' => 'Kiosco', 'slug' => 'kiosco'],
            ['name' => 'Restaurante', 'slug' => 'restaurante'],
            ['name' => 'Pescadería', 'slug' => 'pescaderia'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name']]
            );
        }

        // Negocio 1: Almacén La Esquina
        $business1 = Business::create([
            'name' => 'Almacén La Esquina',
            'slug' => 'almacen-la-esquina',
            'category_id' => Category::where('slug', 'almacen')->first()->id,
            'description' => 'Frutas y verduras frescas todos los días, traídas directamente del Mercado Central. También contamos con fiambrería artesanal, quesos, productos de almacén y conservas. Pedidos a domicilio en Berisso sin cargo mínimo.',
            'address' => 'Calle 7 y 22, Berisso',
            'hours' => 'Lun–Sáb 7:30–13:00 y 16:00–19:30',
            'phone' => '2214890123',
            'website' => null,
            'email_lugar' => 'laesquina.berisso@gmail.com',
            'image' => 'https://images.pexels.com/photos/27458727/pexels-photo-27458727.jpeg',
            'user_id' => $user->id,
            'published' => true,
        ]);

        // Imágenes adicionales para Almacén La Esquina
        $imagenes1 = [
            'https://images.pexels.com/photos/27458727/pexels-photo-27458727.jpeg',
            'https://images.pexels.com/photos/27458728/pexels-photo-27458728.jpeg',
            'https://images.pexels.com/photos/26907770/pexels-photo-26907770.jpeg',
            'https://images.pexels.com/photos/1036857/pexels-photo-1036857.jpeg',
            'https://images.pexels.com/photos/13982933/pexels-photo-13982933.jpeg',
        ];

        foreach ($imagenes1 as $index => $img) {
            BusinessImage::create([
                'business_id' => $business1->id,
                'image_url' => $img,
                'order' => $index,
            ]);
        }

        // Negocio 2: Verdulería El Jardín
        $business2 = Business::create([
            'name' => 'Verdulería El Jardín',
            'slug' => 'verduleria-el-jardin',
            'category_id' => Category::where('slug', 'verduleria-fruteria')->first()->id,
            'description' => 'Productos orgánicos y de estación. Trabajamos directamente con productores de la zona para ofrecerte la mejor calidad. Tenemos frutas, verduras, legumbres y hierbas aromáticas.',
            'address' => 'Calle 8 y 63, Berisso',
            'hours' => 'Lun a Sab 8:00–20:00, Dom 9:00–13:00',
            'phone' => '2214567890',
            'website' => 'https://eljardin.com.ar',
            'email_lugar' => 'eljardin@gmail.com',
            'image' => 'https://images.pexels.com/photos/12042556/pexels-photo-12042556.jpeg',
            'user_id' => $user->id,
            'published' => true,
        ]);

        $imagenes2 = [
            'https://images.pexels.com/photos/12042556/pexels-photo-12042556.jpeg',
            'https://images.pexels.com/photos/12042557/pexels-photo-12042557.jpeg',
            'https://images.pexels.com/photos/4383912/pexels-photo-4383912.jpeg',
            'https://images.pexels.com/photos/1132047/pexels-photo-1132047.jpeg',
        ];

        foreach ($imagenes2 as $index => $img) {
            BusinessImage::create([
                'business_id' => $business2->id,
                'image_url' => $img,
                'order' => $index,
            ]);
        }

        // Negocio 3: Panadería La Familia
        $business3 = Business::create([
            'name' => 'Panadería La Familia',
            'slug' => 'panaderia-la-familia',
            'category_id' => Category::where('slug', 'panaderia')->first()->id,
            'description' => 'Pan artesanal, facturas, tortas y masas finas. Todo hecho con amor y los mejores ingredientes. Probá nuestras medialunas de manteca y pan casero.',
            'address' => 'Calle 12 y 65, Berisso',
            'hours' => 'Lun a Dom 6:00–21:00',
            'phone' => '2214778899',
            'website' => null,
            'email_lugar' => 'panaderiafamilia@gmail.com',
            'image' => 'https://images.pexels.com/photos/26333319/pexels-photo-26333319.jpeg',
            'user_id' => $user->id,
            'published' => true,
        ]);

        $imagenes3 = [
            'https://images.pexels.com/photos/26333319/pexels-photo-26333319.jpeg',
            'https://images.pexels.com/photos/26333320/pexels-photo-26333320.jpeg',
            'https://images.pexels.com/photos/1775043/pexels-photo-1775043.jpeg',
            'https://images.pexels.com/photos/2144112/pexels-photo-2144112.jpeg',
        ];

        foreach ($imagenes3 as $index => $img) {
            BusinessImage::create([
                'business_id' => $business3->id,
                'image_url' => $img,
                'order' => $index,
            ]);
        }

        // Negocio 4: Pescadería El Puerto
        $business4 = Business::create([
            'name' => 'Pescadería El Puerto',
            'slug' => 'pescaderia-el-puerto',
            'category_id' => Category::where('slug', 'pescaderia')->first()->id,
            'description' => 'Pescados y mariscos frescos del Río de la Plata. Entregas a domicilio en Berisso y Ensenada. Pescado del día, langostinos, calamares y más.',
            'address' => 'Calle 1 y 53, Berisso',
            'hours' => 'Mar a Dom 9:00–18:00',
            'phone' => '2214332211',
            'website' => null,
            'email_lugar' => 'elpuerto.pesca@gmail.com',
            'image' => 'https://images.pexels.com/photos/23543021/pexels-photo-23543021.jpeg',
            'user_id' => $user->id,
            'published' => true,
        ]);

        $imagenes4 = [
            'https://images.pexels.com/photos/23543021/pexels-photo-23543021.jpeg',
            'https://images.pexels.com/photos/23543022/pexels-photo-23543022.jpeg',
            'https://images.pexels.com/photos/4628719/pexels-photo-4628719.jpeg',
        ];

        foreach ($imagenes4 as $index => $img) {
            BusinessImage::create([
                'business_id' => $business4->id,
                'image_url' => $img,
                'order' => $index,
            ]);
        }
    }
}