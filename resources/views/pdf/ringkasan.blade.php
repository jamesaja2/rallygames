<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Ringkasan</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
    </style>
</head>
<body>
    <h3>Laporan Ringkasan Transaksi Peserta</h3>
    <table>
        <thead>
            <tr>
                <th>No Peserta</th>
                <th>Jumlah Soal Dibeli</th>
                <th>Jumlah Soal Dijual</th>
                <th>Total Modal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td>{{ $d['kode_peserta'] }}</td>
                    <td>{{ $d['jumlah_beli'] }}</td>
                    <td>{{ $d['jumlah_jual'] }}</td>
                    <td>Rp {{ number_format($d['total_modal'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
