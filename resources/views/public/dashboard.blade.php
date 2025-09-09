<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHSC - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center mr-4 border border-gray-200">
                        <img src="{{ asset('logo.png') }}" alt="SHSC Logo" class="w-8 h-8 object-contain">
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">SHSC 2025 Dashboard</h1>
                </div>
                <div class="text-gray-600 text-sm flex items-center">
                    <i class="fas fa-clock mr-2 text-gray-400"></i>
                    <span id="current-time"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <!-- Menu Cards -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <!-- Transaksi Card -->
            <a href="{{ route('public.transaksi') }}" class="group">
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-gray-300 hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-50 border border-green-200 w-16 h-16 rounded-xl flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900">Transaksi</h3>
                            <p class="text-gray-500">Beli & Jual Soal</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">Lakukan transaksi beli dan jual soal dengan sistem otomatis</p>
                </div>
            </a>

            <!-- Mutasi Card -->
            <a href="{{ route('public.mutasi') }}" class="group">
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-gray-300 hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-50 border border-blue-200 w-16 h-16 rounded-xl flex items-center justify-center">
                            <i class="fas fa-list-alt text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900">Mutasi</h3>
                            <p class="text-gray-500">Cek Riwayat Tim</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">Lihat riwayat transaksi dan mutasi saldo setiap tim</p>
                </div>
            </a>

            <!-- Leaderboard Card -->
            <div class="group cursor-pointer" onclick="toggleLeaderboard()">
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-gray-300 hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                    <div class="flex items-center mb-4">
                        <div class="bg-amber-50 border border-amber-200 w-16 h-16 rounded-xl flex items-center justify-center">
                            <i class="fas fa-crown text-amber-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900">Leaderboard</h3>
                            <p class="text-gray-500">Ranking Tim</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">Lihat ranking tim berdasarkan saldo terkini</p>
                </div>
            </div>
        </div>

        <!-- Leaderboard Section -->
        <div id="leaderboard-section" class="hidden">
            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-trophy mr-3 text-amber-600"></i>
                        Leaderboard Realtime
                    </h2>
                    <button onclick="refreshLeaderboard()" class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
                </div>
                <div id="leaderboard-content" class="space-y-3">
                    <!-- Leaderboard will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Audio Elements -->
    <audio id="success-sound" preload="auto">
        <source src="{{ asset('sounds/success.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="fail-sound" preload="auto">
        <source src="{{ asset('sounds/fail.mp3') }}" type="audio/mpeg">
    </audio>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID');
            document.getElementById('current-time').textContent = timeString;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Toggle leaderboard
        function toggleLeaderboard() {
            const section = document.getElementById('leaderboard-section');
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                refreshLeaderboard();
            } else {
                section.classList.add('hidden');
            }
        }

        // Refresh leaderboard
        function refreshLeaderboard() {
            fetch('{{ route("public.leaderboard") }}')
                .then(response => response.json())
                .then(data => {
                    const content = document.getElementById('leaderboard-content');
                    content.innerHTML = '';
                    
                    data.forEach((peserta, index) => {
                        const position = index + 1;
                        let badgeColor = 'bg-gray-500';
                        let icon = 'fas fa-medal';
                        
                        if (position === 1) {
                            badgeColor = 'bg-yellow-500';
                            icon = 'fas fa-crown';
                        } else if (position === 2) {
                            badgeColor = 'bg-gray-400';
                        } else if (position === 3) {
                            badgeColor = 'bg-yellow-600';
                        }
                        
                        const row = document.createElement('div');
                        row.className = 'flex items-center justify-between bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-all border border-gray-100';
                        row.innerHTML = `
                            <div class="flex items-center">
                                <div class="${badgeColor} w-10 h-10 rounded-full flex items-center justify-center mr-4 border-2 border-white shadow-sm">
                                    <i class="${icon} text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-gray-900 font-semibold">${peserta.nama_tim}</h4>
                                    <p class="text-gray-500 text-sm">${peserta.smp_asal}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-gray-900 font-bold text-lg">Rp ${new Intl.NumberFormat('id-ID').format(peserta.saldo)}</div>
                                <div class="text-gray-500 text-sm">#${position}</div>
                            </div>
                        `;
                        content.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching leaderboard:', error);
                });
        }

        // Auto-refresh leaderboard every 5 seconds if visible
        setInterval(() => {
            const section = document.getElementById('leaderboard-section');
            if (!section.classList.contains('hidden')) {
                refreshLeaderboard();
            }
        }, 5000);
    </script>
</body>
</html>
