<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';



    protected static function booted(): void
    {
        static::creating(function ($transaksi) {
            $peserta = Peserta::find($transaksi->peserta_id);
            $soal = Soal::where('kode_soal', $transaksi->kode_soal)->first();

            if ($transaksi->keterangan === 'Beli') {
                $transaksi->debet = 0;
                $transaksi->kredit = $soal->harga_beli ?? 0;
            } elseif ($transaksi->keterangan === 'Jual - Benar') {
                $transaksi->debet = $soal->harga_benar ?? 0;
                $transaksi->kredit = 0;
            } elseif ($transaksi->keterangan === 'Jual - Salah') {
                $transaksi->debet = $soal->harga_salah ?? 0;
                $transaksi->kredit = 0;
            } elseif ($transaksi->keterangan === 'Modal') {
                $transaksi->debet = $transaksi->debet ?? 0;
                $transaksi->kredit = 0;
            }

            $transaksi->total_saldo = $peserta->saldo + $transaksi->debet - $transaksi->kredit;

            // Update saldo peserta
            $peserta->saldo = $transaksi->total_saldo;
            $peserta->save();
        });
    }
    protected $fillable = [
        'peserta_id',
        'kode_soal',
        'jenis',       // 'jual', 'beli', atau 'modal'
        'debet',
        'kredit',
        'saldo',
        'keterangan',
    ];

    public function peserta()
{
    return $this->belongsTo(\App\Models\Peserta::class);
}



}
