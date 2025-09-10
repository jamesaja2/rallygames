<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    protected $table = 'peserta';
    protected $fillable = [
    'kode_peserta',
    'nama_tim',
    'smp_asal',
    'saldo',
    'anggota_1',
    'anggota_2',
    'anggota_3'
];

public function transaksi()
{
    return $this->hasMany(Transaksi::class);
}



}

