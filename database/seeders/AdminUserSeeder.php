<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@berisso.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('admin123'),
                'is_admin' => true,
            ]
        );
        
        $this->command->info('✅ Usuario administrador creado:');
        $this->command->info('📧 Email: admin@berisso.com');
        $this->command->info('🔑 Contraseña: admin123');
    }
}