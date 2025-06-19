<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';
    protected $fillable = [
    'kode_soal',
    'mapel',
    'harga_beli',
    'harga_benar',
    'harga_salah',
];

}