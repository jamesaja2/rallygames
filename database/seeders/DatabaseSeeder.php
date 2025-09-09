<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminSeeder::class,
            PesertaSeeder::class,
            SoalSeeder::class,
            TransaksiSeeder::class,
        ]);

        $this->command->info('🎉 All seeders completed successfully!');
        $this->command->info('📊 Sample data for Rally Games has been created.');
        $this->command->info('👤 Admin accounts have been created.');
    }
}
