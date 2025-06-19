<?php

namespace App\Imports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PesertaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Peserta([
            'kode_peserta' => $row['kode_peserta'],
            'nama_tim'     => $row['nama_tim'],
            'smp_asal'     => $row['smp_asal'],
            'saldo'        => (int) $row['saldo'],
            'anggota_1'    => $row['anggota_1'] ?? null,
            'anggota_2'    => $row['anggota_2'] ?? null,
            'anggota_3'    => $row['anggota_3'] ?? null,
        ]);
    }
}