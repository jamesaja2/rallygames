<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Peserta;
use App\Models\Transaksi;

class PesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pesertas = [
            [
                'kode_peserta' => 'TIM001',
                'nama_tim' => 'Einstein Squad',
                'smp_asal' => 'SMP Negeri 1 Jakarta',
                'saldo' => 25000,
                'anggota_1' => 'Ahmad Rizki',
                'anggota_2' => 'Siti Nurhaliza',
                'anggota_3' => 'Budi Santoso'
            ],
            [
                'kode_peserta' => 'TIM002',
                'nama_tim' => 'Newton Warriors',
                'smp_asal' => 'SMP Negeri 2 Jakarta',
                'saldo' => 30000,
                'anggota_1' => 'Dewi Sartika',
                'anggota_2' => 'Rama Wijaya',
                'anggota_3' => 'Lisa Permata'
            ],
            [
                'kode_peserta' => 'TIM003',
                'nama_tim' => 'Galileo Genius',
                'smp_asal' => 'SMP Negeri 3 Jakarta',
                'saldo' => 20000,
                'anggota_1' => 'Fajar Nugraha',
                'anggota_2' => 'Maya Indira',
                'anggota_3' => 'Andi Pratama'
            ],
            [
                'kode_peserta' => 'TIM004',
                'nama_tim' => 'Darwin Explorers',
                'smp_asal' => 'SMP Negeri 4 Jakarta',
                'saldo' => 35000,
                'anggota_1' => 'Nina Sari',
                'anggota_2' => 'Doni Hermawan',
                'anggota_3' => 'Rika Melati'
            ],
            [
                'kode_peserta' => 'TIM005',
                'nama_tim' => 'Tesla Innovators',
                'smp_asal' => 'SMP Negeri 5 Jakarta',
                'saldo' => 28000,
                'anggota_1' => 'Arif Budiman',
                'anggota_2' => 'Lina Safitri',
                'anggota_3' => 'Yoga Pratama'
            ],
            [
                'kode_peserta' => 'TIM006',
                'nama_tim' => 'Curie Champions',
                'smp_asal' => 'SMP Negeri 6 Jakarta',
                'saldo' => 22000,
                'anggota_1' => 'Sari Dewi',
                'anggota_2' => 'Hadi Purnomo',
                'anggota_3' => 'Tina Wijayanti'
            ]
        ];

        foreach ($pesertas as $pesertaData) {
            $peserta = Peserta::create($pesertaData);
            
            // Buat transaksi modal awal
            Transaksi::create([
                'peserta_id' => $peserta->id,
                'kode_soal' => null,
                'keterangan' => 'Modal',
                'debet' => $pesertaData['saldo'],
                'kredit' => 0,
                'total_saldo' => $pesertaData['saldo'],
            ]);
        }

        $this->command->info('Sample peserta dan modal awal berhasil dibuat!');
    }
}
