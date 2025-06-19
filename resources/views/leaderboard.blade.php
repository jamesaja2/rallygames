<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 to-purple-900 min-h-screen text-white py-10 px-6">
    <h2 class="text-3xl font-bold text-center mb-10">🏆 Leaderboard Peserta</h2>

    @php
    $top3 = $peserta->take(3);
@endphp

<div class="flex flex-col md:flex-row justify-center items-end gap-6 mb-12">
    {{-- Rank 2 --}}
    @if(isset($top3[1]))
    <div class="bg-slate-800 rounded-xl text-center p-4 w-full md:w-60 relative border-2 border-slate-700 scale-95 md:-mb-6">
        <img src="{{ asset('assets/2.png') }}" class="w-[86px] md:w-[86px] absolute -top-6 left-1/2 -translate-x-1/2" alt="Rank 2">
        <h3 class="text-lg font-bold mt-10">{{ $top3[1]->nama_tim }}</h3>
        <p class="text-sm text-gray-300">{{ $top3[1]->smp_asal }}</p>
        <div class="flex justify-center flex-wrap gap-2 mt-2">
            @foreach([$top3[1]->anggota_1, $top3[1]->anggota_2, $top3[1]->anggota_3] as $anggota)
                <span class="bg-white text-black text-xs font-semibold rounded-full px-2 py-1">{{ $anggota }}</span>
            @endforeach
        </div>
        <p class="mt-3 text-base font-bold text-green-400">Rp {{ number_format($top3[1]->saldo, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">Rank #2</p>
    </div>
    @endif

    {{-- Rank 1 --}}
    @if(isset($top3[0]))
    <div class="bg-slate-800 rounded-xl text-center p-6 w-full md:w-72 relative border-2 border-yellow-400 scale-105 z-10">
        <img src="{{ asset('assets/1.png') }}" class="w-[90px] md:w-[90px] absolute -top-7 left-1/2 -translate-x-1/2" alt="Rank 1">
        <h3 class="text-xl font-bold mt-12">{{ $top3[0]->nama_tim }}</h3>
        <p class="text-sm text-gray-300">{{ $top3[0]->smp_asal }}</p>
        <div class="flex justify-center flex-wrap gap-2 mt-3">
            @foreach([$top3[0]->anggota_1, $top3[0]->anggota_2, $top3[0]->anggota_3] as $anggota)
                <span class="bg-white text-black text-sm font-semibold rounded-full px-3 py-1">{{ $anggota }}</span>
            @endforeach
        </div>
        <p class="mt-4 text-lg font-bold text-green-400">Rp {{ number_format($top3[0]->saldo, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-400 mt-1">Rank #1</p>
    </div>
    @endif

    {{-- Rank 3 --}}
    @if(isset($top3[2]))
    <div class="bg-slate-800 rounded-xl text-center p-4 w-full md:w-56 relative border-2 border-slate-700 scale-90 md:-mb-3">
        <img src="{{ asset('assets/3.png') }}" class="w-[80px] md:w-[80px] absolute -top-5 left-1/2 -translate-x-1/2" alt="Rank 3">
        <h3 class="text-base font-bold mt-10">{{ $top3[2]->nama_tim }}</h3>
        <p class="text-sm text-gray-300">{{ $top3[2]->smp_asal }}</p>
        <div class="flex justify-center flex-wrap gap-2 mt-2">
            @foreach([$top3[2]->anggota_1, $top3[2]->anggota_2, $top3[2]->anggota_3] as $anggota)
                <span class="bg-white text-black text-xs font-semibold rounded-full px-2 py-1">{{ $anggota }}</span>
            @endforeach
        </div>
        <p class="mt-3 text-base font-bold text-green-400">Rp {{ number_format($top3[2]->saldo, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">Rank #3</p>
    </div>
    @endif
</div>


    {{-- Rank 4+ --}}
<div class="space-y-4 md:space-y-0 md:overflow-x-auto md:rounded-xl md:shadow-lg md:border md:border-slate-700">
    {{-- Mobile card style --}}
    <div class="flex flex-col gap-4 md:hidden">
        @foreach($peserta->skip(3)->values() as $index => $p)
        <div class="bg-slate-800 rounded-xl p-4 shadow hover:bg-slate-700 transition">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-400 font-semibold">Rank #{{ $index + 4 }}</span>
                @if($index < 7)
                    <span class="text-xs px-2 py-1 rounded-full bg-yellow-400 text-black font-bold">Top 10</span>
                @endif
            </div>
            <h4 class="text-lg font-bold text-white">{{ $p->nama_tim }}</h4>
            <p class="text-sm text-gray-300">{{ $p->smp_asal }}</p>
            <div class="flex flex-wrap gap-2 my-2">
                @foreach([$p->anggota_1, $p->anggota_2, $p->anggota_3] as $anggota)
                    <span class="bg-white text-black text-xs font-medium rounded-full px-2 py-1">{{ $anggota }}</span>
                @endforeach
            </div>
            <p class="text-right text-green-400 font-bold text-sm">Rp {{ number_format($p->saldo, 0, ',', '.') }}</p>
        </div>
        @endforeach
    </div>

    {{-- Desktop table style --}}
    <table class="hidden md:table min-w-full text-sm text-left text-white">
        <thead class="bg-slate-700 text-xs uppercase text-gray-300">
            <tr>
                <th class="p-4 text-center">Rank</th>
                <th class="p-4">Nama Tim</th>
                <th class="p-4">SMP Asal</th>
                <th class="p-4">Anggota</th>
                <th class="p-4 text-right">Saldo</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-700 bg-slate-800">
            @foreach($peserta->skip(3)->values() as $index => $p)
            <tr class="hover:bg-slate-700 transition duration-200">
                <td class="p-4 text-center font-semibold text-gray-300">{{ $index + 4 }}</td>
                <td class="p-4 font-semibold">
                    {{ $p->nama_tim }}
                    @if($index < 7)
                        <span class="ml-2 inline-block text-xs bg-yellow-400 text-black px-2 py-1 rounded-full font-bold">Top 10</span>
                    @endif
                </td>
                <td class="p-4 text-gray-300">{{ $p->smp_asal }}</td>
                <td class="p-4">
                    <div class="flex flex-wrap gap-2">
                        @foreach([$p->anggota_1, $p->anggota_2, $p->anggota_3] as $anggota)
                            <span class="bg-white text-black text-xs font-medium rounded-full px-2 py-1">{{ $anggota }}</span>
                        @endforeach
                    </div>
                </td>
                <td class="p-4 text-right text-green-400 font-bold">Rp {{ number_format($p->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


</body>
</html>
