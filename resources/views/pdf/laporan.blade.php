<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peserta</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        .identitas { text-align: left; }
    </style>
</head>
<body>
    <div class="identitas">
        <strong>{{ $peserta->kode_peserta }}</strong><br>
        {{ $peserta->smp_asal }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Keterangan</th>
                <th>Kode Soal</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
    @foreach($transaksi as $i => $row)
    <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $row['keterangan'] }}</td>
        <td>{{ $row['kode_soal'] }}</td>
        <td>{{ number_format($row['debet']) }}</td>
        <td>{{ number_format($row['kredit']) }}</td>
        <td>{{ number_format($row['saldo']) }}</td>

    </tr>
    @endforeach
</tbody>

    </table>
</body>
</html>
