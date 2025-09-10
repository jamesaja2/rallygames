<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #000; padding: 6px; }
    </style>
</head>
<body>
    <h2>Laporan Transaksi</h2>
    <p><strong>No Peserta:</strong> {{ $peserta->kode_peserta }}<br>
       <strong>SMP Asal:</strong> {{ $peserta->smp_asal }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Keterangan</th>
                <th>Kode Soal</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Total Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $t->keterangan }}</td>
                <td>{{ $t->kode_soal }}</td>
                <td>{{ number_format($t->debet, 0, ',', '.') }}</td>
                <td>{{ number_format($t->kredit, 0, ',', '.') }}</td>
                <td>{{ number_format($t->total_saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
