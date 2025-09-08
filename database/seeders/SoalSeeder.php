<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Soal;

class SoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $soals = [
            // Matematika - Pilihan Ganda
            [
                'kode_soal' => 'MAT001',
                'mapel' => 'Matematika',
                'tipe_soal' => 'Pilihan Ganda',
                'harga_beli' => 5000,
                'harga_benar' => 8000,
                'harga_salah' => 3000,
                'kunci_jawaban' => 'C',
                'pilihan_jawaban' => [
                    ['huruf' => 'A', 'teks' => '15'],
                    ['huruf' => 'B', 'teks' => '20'],
                    ['huruf' => 'C', 'teks' => '25'],
                    ['huruf' => 'D', 'teks' => '30'],
                    ['huruf' => 'E', 'teks' => '35']
                ]
            ],
            [
                'kode_soal' => 'MAT002',
                'mapel' => 'Matematika',
                'tipe_soal' => 'Pilihan Ganda',
                'harga_beli' => 6000,
                'harga_benar' => 9000,
                'harga_salah' => 3500,
                'kunci_jawaban' => 'B',
                'pilihan_jawaban' => [
                    ['huruf' => 'A', 'teks' => '144'],
                    ['huruf' => 'B', 'teks' => '169'],
                    ['huruf' => 'C', 'teks' => '196'],
                    ['huruf' => 'D', 'teks' => '225'],
                    ['huruf' => 'E', 'teks' => '256']
                ]
            ],

            // Fisika - Pilihan Ganda
            [
                'kode_soal' => 'FIS001',
                'mapel' => 'Fisika',
                'tipe_soal' => 'Pilihan Ganda',
                'harga_beli' => 7000,
                'harga_benar' => 10000,
                'harga_salah' => 4000,
                'kunci_jawaban' => 'A',
                'pilihan_jawaban' => [
                    ['huruf' => 'A', 'teks' => '9.8 m/s²'],
                    ['huruf' => 'B', 'teks' => '9.0 m/s²'],
                    ['huruf' => 'C', 'teks' => '10.0 m/s²'],
                    ['huruf' => 'D', 'teks' => '8.8 m/s²'],
                    ['huruf' => 'E', 'teks' => '10.8 m/s²']
                ]
            ],

            // Biologi - Pilihan Ganda Kompleks
            [
                'kode_soal' => 'BIO001',
                'mapel' => 'Biologi',
                'tipe_soal' => 'Pilihan Ganda Kompleks',
                'harga_beli' => 8000,
                'harga_benar' => 12000,
                'harga_salah' => 4500,
                'kunci_jawaban' => '["A","C","D"]',
                'pilihan_jawaban' => [
                    ['huruf' => 'A', 'teks' => 'Inti sel mengandung DNA'],
                    ['huruf' => 'B', 'teks' => 'Mitokondria tidak memiliki membran'],
                    ['huruf' => 'C', 'teks' => 'Ribosom tempat sintesis protein'],
                    ['huruf' => 'D', 'teks' => 'Kloroplas mengandung klorofil'],
                    ['huruf' => 'E', 'teks' => 'Vakuola hanya ada pada sel hewan']
                ]
            ],

            // Matematika - Essai
            [
                'kode_soal' => 'MAT003',
                'mapel' => 'Matematika',
                'tipe_soal' => 'Essai',
                'harga_beli' => 9000,
                'harga_benar' => 15000,
                'harga_salah' => 5000,
                'kunci_jawaban' => 'x = 5, y = 3',
                'pilihan_jawaban' => null
            ],

            // Fisika - Essai
            [
                'kode_soal' => 'FIS002',
                'mapel' => 'Fisika',
                'tipe_soal' => 'Essai',
                'harga_beli' => 10000,
                'harga_benar' => 16000,
                'harga_salah' => 6000,
                'kunci_jawaban' => 'F = ma = 10 × 2 = 20 Newton',
                'pilihan_jawaban' => null
            ],

            // Kimia - Pilihan Ganda
            [
                'kode_soal' => 'KIM001',
                'mapel' => 'Kimia',
                'tipe_soal' => 'Pilihan Ganda',
                'harga_beli' => 6500,
                'harga_benar' => 9500,
                'harga_salah' => 3800,
                'kunci_jawaban' => 'D',
                'pilihan_jawaban' => [
                    ['huruf' => 'A', 'teks' => 'H₂O'],
                    ['huruf' => 'B', 'teks' => 'CO₂'],
                    ['huruf' => 'C', 'teks' => 'NaCl'],
                    ['huruf' => 'D', 'teks' => 'CaCO₃'],
                    ['huruf' => 'E', 'teks' => 'NH₃']
                ]
            ],

            // Bahasa Indonesia - Pilihan Ganda
            [
                'kode_soal' => 'IND001',
                'mapel' => 'Bahasa Indonesia',
                'tipe_soal' => 'Pilihan Ganda',
                'harga_beli' => 5500,
                'harga_benar' => 8500,
                'harga_salah' => 3200,
                'kunci_jawaban' => 'B',
                'pilihan_jawaban' => [
                    ['huruf' => 'A', 'teks' => 'Kata sifat'],
                    ['huruf' => 'B', 'teks' => 'Kata kerja'],
                    ['huruf' => 'C', 'teks' => 'Kata benda'],
                    ['huruf' => 'D', 'teks' => 'Kata keterangan'],
                    ['huruf' => 'E', 'teks' => 'Kata sambung']
                ]
            ]
        ];

        foreach ($soals as $soalData) {
            Soal::create($soalData);
        }

        $this->command->info('Sample soal berhasil dibuat!');
        $this->command->info('Total soal: ' . count($soals));
    }
}
