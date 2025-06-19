<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard SHSC</title>
    <style>
        body { font-family: sans-serif; background: #f5f5f5; padding: 2rem; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        h1 { text-align: center; margin-bottom: 2rem; }
    </style>
</head>
<body>
    <h1>Leaderboard SHSC</h1>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Peserta</th>
                <th>Nama Tim</th>
                <th>SMP Asal</th>
                <th>Anggota</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peserta as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->kode_peserta }}</td>
                <td>{{ $p->nama_tim }}</td>
                <td>{{ $p->smp_asal }}</td>
                <td>
                    {{ $p->anggota_1 }}<br>
                    {{ $p->anggota_2 }}<br>
                    {{ $p->anggota_3 }}
                </td>
                <td>Rp {{ number_format($p->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
