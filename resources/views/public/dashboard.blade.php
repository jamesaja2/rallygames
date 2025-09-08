<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rally Games - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 min-h-screen">
    <!-- Header -->
    <div class="bg-white/10 backdrop-blur-lg border-b border-white/20">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 w-12 h-12 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-trophy text-white text-xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-white">Rally Games Dashboard</h1>
                </div>
                <div class="text-white text-sm">
                    <i class="fas fa-clock mr-2"></i>
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
                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    <div class="flex items-center mb-4">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 w-16 h-16 rounded-full flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-white text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-white">Transaksi</h3>
                            <p class="text-gray-300">Beli & Jual Soal</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">Lakukan transaksi beli dan jual soal dengan sistem otomatis</p>
                </div>
            </a>

            <!-- Mutasi Card -->
            <a href="{{ route('public.mutasi') }}" class="group">
                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    <div class="flex items-center mb-4">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-600 w-16 h-16 rounded-full flex items-center justify-center">
                            <i class="fas fa-list-alt text-white text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-white">Mutasi</h3>
                            <p class="text-gray-300">Cek Riwayat Tim</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">Lihat riwayat transaksi dan mutasi saldo setiap tim</p>
                </div>
            </a>

            <!-- Leaderboard Card -->
            <div class="group cursor-pointer" onclick="toggleLeaderboard()">
                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    <div class="flex items-center mb-4">
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-600 w-16 h-16 rounded-full flex items-center justify-center">
                            <i class="fas fa-crown text-white text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-white">Leaderboard</h3>
                            <p class="text-gray-300">Ranking Tim</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">Lihat ranking tim berdasarkan saldo terkini</p>
                </div>
            </div>
        </div>

        <!-- Leaderboard Section -->
        <div id="leaderboard-section" class="hidden">
            <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-trophy mr-3"></i>
                        Leaderboard Realtime
                    </h2>
                    <button onclick="refreshLeaderboard()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg transition-colors">
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
                        row.className = 'flex items-center justify-between bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-all';
                        row.innerHTML = `
                            <div class="flex items-center">
                                <div class="${badgeColor} w-10 h-10 rounded-full flex items-center justify-center mr-4">
                                    <i class="${icon} text-white"></i>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold">${peserta.nama_tim}</h4>
                                    <p class="text-gray-400 text-sm">${peserta.smp_asal}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-white font-bold text-lg">Rp ${new Intl.NumberFormat('id-ID').format(peserta.saldo)}</div>
                                <div class="text-gray-400 text-sm">#${position}</div>
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
