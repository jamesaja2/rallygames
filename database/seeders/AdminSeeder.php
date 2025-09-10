<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role super_admin sudah ada
        $adminRole = Role::firstOrCreate(['name' => 'super_admin']);

        // Buat user admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@hotsains.com'],
            [
                'name' => 'Admin HotSains',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign role ke admin
        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }

        // Buat user admin tambahan
        $admin2 = User::firstOrCreate(
            ['email' => 'jamestimothyaja@gmail.com'],
            [
                'name' => 'James Timothy',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        if (!$admin2->hasRole('super_admin')) {
            $admin2->assignRole('super_admin');
        }

        $this->command->info('✅ Admin users created successfully!');
        $this->command->info('📧 Email: admin@hotsains.com | Password: admin123');
        $this->command->info('📧 Email: jamestimothyaja@gmail.com | Password: admin123');
    }
}