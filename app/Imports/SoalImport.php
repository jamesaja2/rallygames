<?php

namespace App\Imports;

use App\Models\Soal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SoalImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Soal([
            'kode_soal'     => $row['kode_soal'],
            'mapel'         => $row['mapel'],
            'tipe_soal'     => $row['tipe_soal'] ?? 'Pilihan Ganda',
            'harga_beli'    => (int) $row['harga_beli'],
            'harga_benar'   => (int) $row['harga_benar'],
            'harga_salah'   => (int) $row['harga_salah'],
            'kunci_jawaban' => $row['kunci_jawaban'] ?? null,
        ]);
    }
}