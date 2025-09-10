<?php

namespace App\Http\Controllers;
use App\Models\Peserta;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function exportRingkasan()
    {
        $pesertas = Peserta::with('transaksi')->get();

        $data = $pesertas->map(function ($peserta) {
            $jumlahBeli = $peserta->transaksi->where('keterangan', 'Beli')->count();
            $jumlahJual = $peserta->transaksi->where('keterangan', '!=', 'Beli')->count();

            return [
                'kode_peserta' => $peserta->kode_peserta,
                'jumlah_beli' => $jumlahBeli,
                'jumlah_jual' => $jumlahJual,
                'total_modal' => $peserta->transaksi->where('keterangan', 'Modal')->sum('debet'),
            ];
        });

        $pdf = Pdf::loadView('pdf.ringkasan', ['data' => $data]);

        return $pdf->download('laporan_ringkasan.pdf');
    }
}
