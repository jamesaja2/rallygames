<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $koordJuri = Role::firstOrCreate(['name' => 'Koord Juri']);
        $panitia = Role::firstOrCreate(['name' => 'Panitia']);

        // Create permissions (optional)
        $permissions = [
            'manage users',
            'manage peserta',
            'manage soal',
            'manage transaksi',
            'view reports'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $superAdmin->givePermissionTo(Permission::all());
        $koordJuri->givePermissionTo(['manage soal', 'manage transaksi', 'view reports']);
        $panitia->givePermissionTo(['manage transaksi', 'view reports']);

        // Create Super Admin user
        // *** GANTI EMAIL DI BAWAH INI DENGAN EMAIL ANDA ***
        $adminEmail = 'admin@rallygames.com'; // GANTI INI dengan email Anda
        
        $adminUser = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123') // GANTI password ini
            ]
        );

        $adminUser->assignRole('Super Admin');

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info("Super Admin user created with email: {$adminEmail}");
        $this->command->info("Default password: password123 (Please change this!)");
    }
}
