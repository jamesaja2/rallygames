<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'dashboard_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Mengatur apakah dashboard publik dapat diakses atau tidak'
            ],
            [
                'key' => 'competition_name',
                'value' => 'Sinlui Hot Science Competition 2025',
                'type' => 'string',
                'description' => 'Nama kompetisi yang sedang berjalan'
            ],
            [
                'key' => 'max_participants_per_team',
                'value' => '3',
                'type' => 'integer',
                'description' => 'Maksimal jumlah anggota per tim'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
