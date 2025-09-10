<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function export(Peserta $peserta)
    {
        $transaksi = $peserta->transaksi()->orderBy('created_at')->get();
        $saldo = 0;
        $data = [];

        foreach ($transaksi as $i => $t) {
            $data[] = [
                'no' => $i + 1,
                'keterangan' => $t->keterangan,
                'kode_soal' => $t->kode_soal,
                'debet' => $t->debet,
                'kredit' => $t->kredit,
                'saldo' => $t->total_saldo,
            ];
        }


        $pdf = Pdf::loadView('pdf.laporan', [
            'peserta' => $peserta,
            'transaksi' => $data,
        ]);

        return $pdf->download("laporan_{$peserta->kode_peserta}.pdf");
    }
}
