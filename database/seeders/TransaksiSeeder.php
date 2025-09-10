<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\Peserta;
use App\Models\Soal;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample transaksi untuk demo (menggunakan logika yang benar: debet = uang keluar, kredit = uang masuk)
        $transaksis = [
            // Tim Einstein Squad (ID: 1) - Modal awal
            [
                'peserta_id' => 1,
                'kode_soal' => null,
                'keterangan' => 'Modal',
                'debet' => 0,
                'kredit' => 50000, // Modal awal 50k
            ],
            // Beli soal MAT001
            [
                'peserta_id' => 1,
                'kode_soal' => 'MAT001',
                'keterangan' => 'Beli',
                'debet' => 2000, // Uang keluar untuk beli soal
                'kredit' => 0,
            ],
            // Jual soal MAT001 dengan benar
            [
                'peserta_id' => 1,
                'kode_soal' => 'MAT001',
                'keterangan' => 'Jual - Benar',
                'debet' => 0,
                'kredit' => 3000, // Uang masuk dari jual soal benar
            ],
            // Beli soal FIS001
            [
                'peserta_id' => 1,
                'kode_soal' => 'FIS001',
                'keterangan' => 'Beli',
                'debet' => 2000,
                'kredit' => 0,
            ],

            // Tim Newton Warriors (ID: 2) - Modal awal
            [
                'peserta_id' => 2,
                'kode_soal' => null,
                'keterangan' => 'Modal',
                'debet' => 0,
                'kredit' => 50000,
            ],
            // Beli soal MAT002
            [
                'peserta_id' => 2,
                'kode_soal' => 'MAT002',
                'keterangan' => 'Beli',
                'debet' => 2000,
                'kredit' => 0,
            ],
            // Jual soal MAT002 dengan salah
            [
                'peserta_id' => 2,
                'kode_soal' => 'MAT002',
                'keterangan' => 'Jual - Salah',
                'debet' => 0,
                'kredit' => 1000, // Uang masuk dari jual soal salah (lebih kecil)
            ],
            // Beli soal BIO001
            [
                'peserta_id' => 2,
                'kode_soal' => 'BIO001',
                'keterangan' => 'Beli',
                'debet' => 2000,
                'kredit' => 0,
            ],
            // Jual soal BIO001 dengan benar
            [
                'peserta_id' => 2,
                'kode_soal' => 'BIO001',
                'keterangan' => 'Jual - Benar',
                'debet' => 0,
                'kredit' => 3000,
            ],

            // Tim Galileo Genius (ID: 3) - Modal awal
            [
                'peserta_id' => 3,
                'kode_soal' => null,
                'keterangan' => 'Modal',
                'debet' => 0,
                'kredit' => 50000,
            ],
            // Beli soal FIS001
            [
                'peserta_id' => 3,
                'kode_soal' => 'FIS001',
                'keterangan' => 'Beli',
                'debet' => 2000,
                'kredit' => 0,
            ],
            // Beli soal KIM001
            [
                'peserta_id' => 3,
                'kode_soal' => 'KIM001',
                'keterangan' => 'Beli',
                'debet' => 2000,
                'kredit' => 0,
            ],
            // Jual soal KIM001 dengan benar
            [
                'peserta_id' => 3,
                'kode_soal' => 'KIM001',
                'keterangan' => 'Jual - Benar',
                'debet' => 0,
                'kredit' => 3000,
            ],

            // Tim Darwin Explorers (ID: 4) - Modal awal
            [
                'peserta_id' => 4,
                'kode_soal' => null,
                'keterangan' => 'Modal',
                'debet' => 0,
                'kredit' => 50000,
            ],
            // Beli soal MAT003
            [
                'peserta_id' => 4,
                'kode_soal' => 'MAT003',
                'keterangan' => 'Beli',
                'debet' => 2000,
                'kredit' => 0,
            ],
            // Beli soal IND001
            [
                'peserta_id' => 4,
                'kode_soal' => 'IND001',
                'keterangan' => 'Beli',
                'debet' => 2000,
                'kredit' => 0,
            ],
            // Jual soal IND001 dengan benar
            [
                'peserta_id' => 4,
                'kode_soal' => 'IND001',
                'keterangan' => 'Jual - Benar',
                'debet' => 0,
                'kredit' => 3000,
            ]
        ];

        foreach ($transaksis as $transaksiData) {
            // Buat transaksi langsung, biarkan model event handle perhitungan saldo
            Transaksi::create([
                'peserta_id' => $transaksiData['peserta_id'],
                'kode_soal' => $transaksiData['kode_soal'],
                'keterangan' => $transaksiData['keterangan'],
                // Jangan isi debet/kredit manual, biarkan model event yang mengisi
            ]);
        }

        $this->command->info('Sample transaksi berhasil dibuat!');
        $this->command->info('Total transaksi: ' . count($transaksis));
    }
}
