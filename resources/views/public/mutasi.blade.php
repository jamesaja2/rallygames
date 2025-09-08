<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rally Games - Cek Mutasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 min-h-screen">
    <!-- Header -->
    <div class="bg-white/10 backdrop-blur-lg border-b border-white/20">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('public.dashboard') }}" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg mr-4 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-white">Cek Mutasi Tim</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Search Section -->
            <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20 mb-8">
                <h2 class="text-2xl font-bold text-white mb-6 text-center">
                    <i class="fas fa-search mr-3"></i>
                    Cari Tim
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-white font-semibold mb-2">Pilih Tim:</label>
                        <div class="relative">
                            <input type="text" id="search-tim" placeholder="Ketik nama tim untuk mencari..." 
                                   class="w-full px-4 py-3 pr-12 bg-white/10 border border-white/30 rounded-xl text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <i class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div id="tim-suggestions" class="space-y-2 max-h-48 overflow-y-auto hidden">
                        @foreach($pesertas as $peserta)
                        <div class="tim-suggestion bg-white/5 hover:bg-white/20 p-4 rounded-xl cursor-pointer transition-all" 
                             data-id="{{ $peserta->id }}" 
                             data-nama="{{ $peserta->nama_tim }}"
                             data-saldo="{{ $peserta->saldo }}">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-white font-semibold">{{ $peserta->nama_tim }}</h4>
                                    <p class="text-gray-400 text-sm">{{ $peserta->smp_asal }} • {{ $peserta->kode_peserta }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-white font-bold">Rp {{ number_format($peserta->saldo, 0, ',', '.') }}</p>
                                    <p class="text-gray-400 text-sm">Saldo Saat Ini</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Selected Tim Info -->
            <div id="selected-tim-info" class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20 mb-8 hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-white" id="selected-tim-nama"></h3>
                        <p class="text-gray-300" id="selected-tim-detail"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-white" id="selected-tim-saldo"></p>
                        <p class="text-gray-400">Saldo Saat Ini</p>
                    </div>
                </div>
            </div>

            <!-- Mutasi Table -->
            <div id="mutasi-section" class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20 hidden">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">
                        <i class="fas fa-list-alt mr-3"></i>
                        Riwayat Transaksi
                    </h2>
                    <button onclick="refreshMutasi()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/20">
                                <th class="text-left text-white font-semibold py-3 px-2">Tanggal</th>
                                <th class="text-left text-white font-semibold py-3 px-2">Kode Soal</th>
                                <th class="text-left text-white font-semibold py-3 px-2">Keterangan</th>
                                <th class="text-right text-white font-semibold py-3 px-2">Debet</th>
                                <th class="text-right text-white font-semibold py-3 px-2">Kredit</th>
                                <th class="text-right text-white font-semibold py-3 px-2">Saldo</th>
                            </tr>
                        </thead>
                        <tbody id="mutasi-table-body">
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>
                </div>
                
                <div id="no-data-message" class="text-center py-8 hidden">
                    <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-400">Belum ada transaksi untuk tim ini</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedTimId = null;
        let allPesertas = @json($pesertas);

        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
        });

        function setupEventListeners() {
            const searchInput = document.getElementById('search-tim');
            const suggestions = document.getElementById('tim-suggestions');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                if (searchTerm.length > 0) {
                    filterAndShowSuggestions(searchTerm);
                    suggestions.classList.remove('hidden');
                } else {
                    suggestions.classList.add('hidden');
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#search-tim') && !e.target.closest('#tim-suggestions')) {
                    suggestions.classList.add('hidden');
                }
            });

            // Tim suggestion clicks
            document.querySelectorAll('.tim-suggestion').forEach(item => {
                item.addEventListener('click', function() {
                    selectTim(this);
                });
            });
        }

        function filterAndShowSuggestions(searchTerm) {
            const suggestions = document.querySelectorAll('.tim-suggestion');
            let hasVisible = false;

            suggestions.forEach(item => {
                const nama = item.dataset.nama.toLowerCase();
                if (nama.includes(searchTerm)) {
                    item.style.display = 'block';
                    hasVisible = true;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide suggestions container based on results
            const container = document.getElementById('tim-suggestions');
            if (hasVisible) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }

        function selectTim(element) {
            selectedTimId = element.dataset.id;
            const nama = element.dataset.nama;
            const saldo = element.dataset.saldo;
            
            // Update search input
            document.getElementById('search-tim').value = nama;
            
            // Hide suggestions
            document.getElementById('tim-suggestions').classList.add('hidden');
            
            // Show selected tim info
            document.getElementById('selected-tim-nama').textContent = nama;
            document.getElementById('selected-tim-detail').textContent = element.querySelector('.text-gray-400').textContent;
            document.getElementById('selected-tim-saldo').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(saldo);
            document.getElementById('selected-tim-info').classList.remove('hidden');
            
            // Load mutasi
            loadMutasi();
        }

        function loadMutasi() {
            if (!selectedTimId) return;

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('peserta_id', selectedTimId);

            fetch('{{ route("public.mutasi.search") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                displayMutasi(data);
                document.getElementById('mutasi-section').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error loading mutasi:', error);
            });
        }

        function displayMutasi(transaksis) {
            const tbody = document.getElementById('mutasi-table-body');
            const noDataMessage = document.getElementById('no-data-message');
            
            tbody.innerHTML = '';
            
            if (transaksis.length === 0) {
                noDataMessage.classList.remove('hidden');
                return;
            } else {
                noDataMessage.classList.add('hidden');
            }

            transaksis.forEach(transaksi => {
                const row = document.createElement('tr');
                row.className = 'border-b border-white/10 hover:bg-white/5 transition-colors';
                
                // Format tanggal
                const date = new Date(transaksi.created_at);
                const formattedDate = date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // Tentukan warna berdasarkan jenis transaksi
                let keteranganClass = 'text-gray-300';
                let debetClass = 'text-green-300';
                let kreditClass = 'text-red-300';
                
                if (transaksi.keterangan.includes('Benar')) {
                    keteranganClass = 'text-green-300';
                } else if (transaksi.keterangan.includes('Salah')) {
                    keteranganClass = 'text-yellow-300';
                } else if (transaksi.keterangan === 'Beli') {
                    keteranganClass = 'text-blue-300';
                } else if (transaksi.keterangan === 'Modal') {
                    keteranganClass = 'text-purple-300';
                }

                row.innerHTML = `
                    <td class="py-3 px-2 text-gray-300 text-sm">${formattedDate}</td>
                    <td class="py-3 px-2 text-white font-mono">${transaksi.kode_soal || '-'}</td>
                    <td class="py-3 px-2 ${keteranganClass} font-semibold">${transaksi.keterangan}</td>
                    <td class="py-3 px-2 text-right ${debetClass} font-semibold">
                        ${transaksi.debet > 0 ? '+Rp ' + new Intl.NumberFormat('id-ID').format(transaksi.debet) : '-'}
                    </td>
                    <td class="py-3 px-2 text-right ${kreditClass} font-semibold">
                        ${transaksi.kredit > 0 ? '-Rp ' + new Intl.NumberFormat('id-ID').format(transaksi.kredit) : '-'}
                    </td>
                    <td class="py-3 px-2 text-right text-white font-bold">
                        Rp ${new Intl.NumberFormat('id-ID').format(transaksi.total_saldo || 0)}
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function refreshMutasi() {
            if (selectedTimId) {
                loadMutasi();
            }
        }
    </script>
</body>
</html>
