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
            $soal = null;
            
            if ($transaksi->kode_soal) {
                $soal = Soal::where('kode_soal', $transaksi->kode_soal)->first();
            }

            // Debet = uang keluar, Kredit = uang masuk
            if ($transaksi->keterangan === 'Beli') {
                $transaksi->debet = $soal->harga_beli ?? 0;
                $transaksi->kredit = 0;
            } elseif ($transaksi->keterangan === 'Jual - Benar') {
                $transaksi->debet = 0;
                $transaksi->kredit = $soal->harga_benar ?? 0;
            } elseif ($transaksi->keterangan === 'Jual - Salah') {
                $transaksi->debet = 0;
                $transaksi->kredit = $soal->harga_salah ?? 0;
            } elseif ($transaksi->keterangan === 'Modal') {
                // Untuk modal, bisa diisi manual atau default
                if (!$transaksi->kredit && !$transaksi->debet) {
                    $transaksi->debet = 0;
                    $transaksi->kredit = 50000; // Default modal
                }
            }

            // Hitung saldo terakhir dari transaksi sebelumnya
            $lastSaldo = $peserta->transaksi()->latest('id')->value('total_saldo') ?? $peserta->saldo;
            $transaksi->total_saldo = $lastSaldo - $transaksi->debet + $transaksi->kredit;

            // Tidak update saldo peserta langsung di sini
        });
    }
    protected $fillable = [
        'peserta_id',
        'kode_soal',
        'keterangan',
        'debet',
        'kredit',
        'total_saldo',
        'modal_amount',
    ];

    public function peserta()
{
    return $this->belongsTo(\App\Models\Peserta::class);
}



}
