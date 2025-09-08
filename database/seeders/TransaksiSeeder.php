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
        // Sample transaksi untuk demo
        $transaksis = [
            // Tim Einstein Squad (ID: 1) beli dan jual beberapa soal
            [
                'peserta_id' => 1,
                'kode_soal' => 'MAT001',
                'keterangan' => 'Beli',
                'kredit' => 2000,
                'debet' => 0,
            ],
            [
                'peserta_id' => 1,
                'kode_soal' => 'MAT001',
                'keterangan' => 'Jual - Benar',
                'kredit' => 0,
                'debet' => 3000,
            ],
            [
                'peserta_id' => 1,
                'kode_soal' => 'FIS001',
                'keterangan' => 'Beli',
                'kredit' => 2000,
                'debet' => 0,
            ],

            // Tim Newton Warriors (ID: 2)
            [
                'peserta_id' => 2,
                'kode_soal' => 'MAT002',
                'keterangan' => 'Beli',
                'kredit' => 2000,
                'debet' => 0,
            ],
            [
                'peserta_id' => 2,
                'kode_soal' => 'MAT002',
                'keterangan' => 'Jual - Salah',
                'kredit' => 0,
                'debet' => 1000,
            ],
            [
                'peserta_id' => 2,
                'kode_soal' => 'BIO001',
                'keterangan' => 'Beli',
                'kredit' => 2000,
                'debet' => 0,
            ],
            [
                'peserta_id' => 2,
                'kode_soal' => 'BIO001',
                'keterangan' => 'Jual - Benar',
                'kredit' => 0,
                'debet' => 3000,
            ],

            // Tim Galileo Genius (ID: 3)
            [
                'peserta_id' => 3,
                'kode_soal' => 'FIS001',
                'keterangan' => 'Beli',
                'kredit' => 2000,
                'debet' => 0,
            ],
            [
                'peserta_id' => 3,
                'kode_soal' => 'KIM001',
                'keterangan' => 'Beli',
                'kredit' => 2000,
                'debet' => 0,
            ],
            [
                'peserta_id' => 3,
                'kode_soal' => 'KIM001',
                'keterangan' => 'Jual - Benar',
                'kredit' => 0,
                'debet' => 3000,
            ],

            // Tim Darwin Explorers (ID: 4)
            [
                'peserta_id' => 4,
                'kode_soal' => 'MAT003',
                'keterangan' => 'Beli',
                'kredit' => 2000,
                'debet' => 0,
            ],
            [
                'peserta_id' => 4,
                'kode_soal' => 'IND001',
                'keterangan' => 'Beli',
                'kredit' => 2000,
                'debet' => 0,
            ],
            [
                'peserta_id' => 4,
                'kode_soal' => 'IND001',
                'keterangan' => 'Jual - Benar',
                'kredit' => 0,
                'debet' => 3000,
            ]
        ];

        foreach ($transaksis as $index => $transaksiData) {
            // Get current peserta saldo
            $peserta = Peserta::find($transaksiData['peserta_id']);
            $currentSaldo = $peserta->saldo;
            
            // Calculate new saldo
            $newSaldo = $currentSaldo + $transaksiData['debet'] - $transaksiData['kredit'];
            
            // Create transaction
            Transaksi::create([
                'peserta_id' => $transaksiData['peserta_id'],
                'kode_soal' => $transaksiData['kode_soal'],
                'keterangan' => $transaksiData['keterangan'],
                'debet' => $transaksiData['debet'],
                'kredit' => $transaksiData['kredit'],
                'total_saldo' => $newSaldo,
            ]);
            
            // Update peserta saldo
            $peserta->update(['saldo' => $newSaldo]);
        }

        $this->command->info('Sample transaksi berhasil dibuat!');
        $this->command->info('Total transaksi: ' . count($transaksis));
    }
}
