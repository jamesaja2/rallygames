<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SHSC - Form Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom styling for select dropdowns */
        select option {
            background-color: #ffffff !important;
            color: #374151 !important;
            padding: 8px !important;
        }
        
        select option:hover {
            background-color: #f3f4f6 !important;
        }
        
        select option:checked {
            background-color: #3b82f6 !important;
            color: #ffffff !important;
        }
        
        /* For better visibility */
        select {
            background-color: #ffffff !important;
            color: #374151 !important;
            border: 1px solid #d1d5db !important;
        }
        
        /* Force jawaban button styles to prevent external CSS conflicts */
        .jawaban-option {
            background-color: #ffffff !important;
            color: #111827 !important;
            border: 2px solid #111827 !important;
            transition: all 0.3s ease !important;
        }
        
        .jawaban-option:hover {
            background-color: #f9fafb !important;
            border-color: #374151 !important;
            color: #111827 !important;
        }
        
        .jawaban-option.selected {
            background-color: #111827 !important;
            color: #ffffff !important;
            border-color: #111827 !important;
        }
        
        .jawaban-kompleks-option {
            background-color: #ffffff !important;
            color: #111827 !important;
            border: 2px solid #111827 !important;
            transition: all 0.3s ease !important;
        }
        
        .jawaban-kompleks-option:hover {
            background-color: #f9fafb !important;
            border-color: #374151 !important;
            color: #111827 !important;
        }
        
        .jawaban-kompleks-option.selected {
            background-color: #111827 !important;
            color: #ffffff !important;
            border-color: #111827 !important;
        }
        
        /* Prevent any purple/indigo colors */
        .jawaban-option:focus,
        .jawaban-kompleks-option:focus {
            outline: none !important;
            ring: none !important;
            box-shadow: none !important;
        }
        
        /* Tim selection styling */
        .tim-item.selected {
            ring: 2px solid #111827 !important;
            ring-color: #111827 !important;
        }
        
        /* Aksi selection styling */
        .aksi-item.selected {
            ring: 4px solid #111827 !important;
            ring-color: #111827 !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('public.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-lg mr-4 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-3 border border-gray-200">
                        <img src="{{ asset('logo.png') }}" alt="SHSC Logo" class="w-5 h-5 object-contain">
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Form Transaksi</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Progress Steps -->
            <div class="flex items-center justify-center mb-8">
                <div class="flex items-center">
                    <div id="step-1" class="step-indicator active flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white font-bold border-2 border-gray-900">1</div>
                    <div class="step-line w-16 h-1 bg-gray-300 mx-2"></div>
                    <div id="step-2" class="step-indicator flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold border-2 border-gray-300">2</div>
                    <div class="step-line w-16 h-1 bg-gray-300 mx-2"></div>
                    <div id="step-3" class="step-indicator flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold border-2 border-gray-300">3</div>
                    <div class="step-line w-16 h-1 bg-gray-300 mx-2"></div>
                    <div id="step-4" class="step-indicator flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold border-2 border-gray-300">4</div>
                </div>
            </div>

            <!-- Form Container -->
            <div class="bg-white rounded-xl p-8 border border-gray-200 shadow-sm">
                <!-- Step 1: Pilih Tim -->
                <div id="step-content-1" class="step-content">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Pilih Tim</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Cari Tim:</label>
                            <input type="text" id="search-tim" placeholder="Ketik nama tim..." 
                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        </div>
                        <div id="tim-list" class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($pesertas as $peserta)
                            <div class="tim-item bg-gray-50 hover:bg-gray-100 p-4 rounded-xl cursor-pointer transition-all border border-gray-200" 
                                 data-id="{{ $peserta->id }}" 
                                 data-nama="{{ $peserta->nama_tim }}"
                                 data-saldo="{{ $peserta->saldo }}">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="text-gray-900 font-semibold">{{ $peserta->nama_tim }}</h4>
                                        <p class="text-gray-600 text-sm">{{ $peserta->smp_asal }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-gray-900 font-bold">Rp {{ number_format($peserta->saldo, 0, ',', '.') }}</p>
                                        <p class="text-gray-500 text-sm">{{ $peserta->kode_peserta }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Step 2: Pilih Aksi -->
                <div id="step-content-2" class="step-content hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Pilih Aksi</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="aksi-item bg-green-50 border-2 border-green-200 p-6 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-100 transition-all" 
                             data-aksi="beli">
                            <div class="text-center">
                                <i class="fas fa-shopping-cart text-green-600 text-4xl mb-4"></i>
                                <h3 class="text-green-700 font-bold text-xl mb-2">BELI</h3>
                                <p class="text-green-600 text-sm">Beli soal untuk tim</p>
                            </div>
                        </div>
                        <div class="aksi-item bg-purple-50 border-2 border-purple-200 p-6 rounded-xl cursor-pointer hover:border-purple-400 hover:bg-purple-100 transition-all" 
                             data-aksi="jual">
                            <div class="text-center">
                                <i class="fas fa-hand-holding-usd text-purple-600 text-4xl mb-4"></i>
                                <h3 class="text-purple-700 font-bold text-xl mb-2">JUAL</h3>
                                <p class="text-purple-600 text-sm">Jual soal dengan jawaban</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Detail Transaksi -->
                <div id="step-content-3" class="step-content hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Detail Transaksi</h2>
                    
                    <!-- Untuk Beli -->
                    <div id="beli-section" class="hidden">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Pilih Soal:</label>
                                <select id="soal-beli" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    <option value="">-- Pilih Soal --</option>
                                    @foreach($soals as $soal)
                                    <option value="{{ $soal->kode_soal }}" data-harga="{{ $soal->harga_beli }}">
                                        {{ $soal->kode_soal }} - {{ $soal->mapel }} (Rp {{ number_format($soal->harga_beli, 0, ',', '.') }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="harga-beli-info" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <p class="text-blue-800">Harga: <span id="harga-beli-text" class="font-bold"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Untuk Jual -->
                    <div id="jual-section" class="hidden">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Pilih Soal:</label>
                                <select id="soal-jual" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    <option value="">-- Pilih Soal --</option>
                                    @foreach($soals as $soal)
                                    <option value="{{ $soal->kode_soal }}" 
                                            data-benar="{{ $soal->harga_benar }}" 
                                            data-salah="{{ $soal->harga_salah }}"
                                            data-tipe="{{ $soal->tipe_soal }}"
                                            data-kunci="{{ $soal->kunci_jawaban }}">
                                        {{ $soal->kode_soal }} - {{ $soal->mapel }} ({{ $soal->tipe_soal }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Input Jawaban - Dynamic based on tipe soal -->
                            <div id="jawaban-container" class="hidden">
                                <!-- Pilihan Ganda -->
                                <div id="jawaban-pilgan" class="hidden">
                                    <label class="block text-gray-700 font-semibold mb-2">Pilih Jawaban:</label>
                                    <div class="grid grid-cols-5 gap-3">
                                        <button type="button" class="jawaban-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 rounded-xl py-3 px-4 text-gray-900 font-bold transition-all shadow-sm" data-value="A">A</button>
                                        <button type="button" class="jawaban-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 rounded-xl py-3 px-4 text-gray-900 font-bold transition-all shadow-sm" data-value="B">B</button>
                                        <button type="button" class="jawaban-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 rounded-xl py-3 px-4 text-gray-900 font-bold transition-all shadow-sm" data-value="C">C</button>
                                        <button type="button" class="jawaban-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 rounded-xl py-3 px-4 text-gray-900 font-bold transition-all shadow-sm" data-value="D">D</button>
                                        <button type="button" class="jawaban-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 rounded-xl py-3 px-4 text-gray-900 font-bold transition-all shadow-sm" data-value="E">E</button>
                                    </div>
                                </div>

                                <!-- Pilihan Ganda Kompleks -->
                                <div id="jawaban-kompleks" class="hidden">
                                    <label class="block text-gray-700 font-semibold mb-2">Pilih Jawaban (Boleh lebih dari satu):</label>
                                    <div class="grid grid-cols-5 gap-3 mb-2">
                                        <button type="button" class="jawaban-kompleks-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 rounded-xl py-3 px-4 text-gray-900 font-bold transition-all shadow-sm" data-value="A">A</button>
                                        <button type="button" class="jawaban-kompleks-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 rounded-xl py-3 px-4 text-gray-900 font-bold transition-all shadow-sm" data-value="B">B</button>
                                        <button type="button" class="jawaban-kompleks-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 rounded-xl py-3 px-4 text-gray-900 font-bold transition-all shadow-sm" data-value="C">C</button>
                                        <button type="button" class="jawaban-kompleks-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 rounded-xl py-3 px-4 text-gray-900 font-bold transition-all shadow-sm" data-value="D">D</button>
                                        <button type="button" class="jawaban-kompleks-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 rounded-xl py-3 px-4 text-gray-900 font-bold transition-all shadow-sm" data-value="E">E</button>
                                    </div>
                                    <p class="text-gray-600 text-sm">Pilihan Anda: <span id="selected-kompleks" class="text-gray-900 font-semibold"></span></p>
                                </div>

                                <!-- Essai -->
                                <div id="jawaban-essai" class="hidden">
                                    <label class="block text-gray-700 font-semibold mb-2">Jawaban Essai:</label>
                                    <textarea id="jawaban-text" placeholder="Tulis jawaban essai Anda di sini..." 
                                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent resize-none" 
                                           rows="4"></textarea>
                                </div>
                                
                                <!-- Hidden input for actual answer -->
                                <input type="hidden" id="jawaban" name="jawaban">
                            </div>
                            <div id="harga-jual-info" class="hidden bg-purple-50 border border-purple-200 rounded-xl p-4">
                                <p class="text-purple-800">Jika Benar: <span id="harga-benar-text" class="font-bold text-green-600"></span></p>
                                <p class="text-purple-800">Jika Salah: <span id="harga-salah-text" class="font-bold text-red-600"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Konfirmasi -->
                <div id="step-content-4" class="step-content hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Konfirmasi Transaksi</h2>
                    <div id="konfirmasi-detail" class="bg-gray-50 rounded-xl p-6 space-y-4 border border-gray-200">
                        <!-- Detail will be filled by JS -->
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-8">
                    <button id="btn-prev" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl transition-colors hidden">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Sebelumnya
                    </button>
                    <button id="btn-next" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-xl transition-colors ml-auto disabled:opacity-50" disabled>
                        Selanjutnya
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    <button id="btn-submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl transition-colors hidden">
                        <i class="fas fa-check mr-2"></i>
                        Submit Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedback-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 max-w-md w-full mx-4 border border-white/20">
            <div class="text-center">
                <div id="feedback-icon" class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <!-- Icon will be set by JS -->
                </div>
                <h3 id="feedback-title" class="text-2xl font-bold text-white mb-4"></h3>
                <p id="feedback-message" class="text-gray-300 mb-6"></p>
                <button onclick="closeFeedbackModal()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-xl transition-colors">
                    OK
                </button>
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
        let currentStep = 1;
        let selectedData = {};

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            updateStepUI();
        });

        function setupEventListeners() {
            // Search tim
            document.getElementById('search-tim').addEventListener('input', function() {
                filterTim(this.value);
            });

            // Tim selection
            document.querySelectorAll('.tim-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectTim(this);
                });
            });

            // Aksi selection
            document.querySelectorAll('.aksi-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectAksi(this.dataset.aksi);
                });
            });

            // Soal selection
            document.getElementById('soal-beli').addEventListener('change', function() {
                updateHargaBeli();
                checkStep3Validity();
            });

            document.getElementById('soal-jual').addEventListener('change', function() {
                updateHargaJual();
                setupJawabanInput();
                checkStep3Validity();
            });

            document.getElementById('jawaban-text').addEventListener('input', function() {
                document.getElementById('jawaban').value = this.value;
                checkStep3Validity();
            });

            // Setup jawaban option buttons
            document.querySelectorAll('.jawaban-option').forEach(button => {
                button.addEventListener('click', function() {
                    // Remove previous selection
                    document.querySelectorAll('.jawaban-option').forEach(btn => {
                        btn.classList.remove('selected');
                    });
                    
                    // Add selection
                    this.classList.add('selected');
                    
                    document.getElementById('jawaban').value = this.dataset.value;
                    checkStep3Validity();
                });
            });

            // Setup jawaban kompleks buttons
            document.querySelectorAll('.jawaban-kompleks-option').forEach(button => {
                button.addEventListener('click', function() {
                    this.classList.toggle('selected');
                    
                    updateKompleksSelection();
                    checkStep3Validity();
                });
            });

            // Navigation buttons
            document.getElementById('btn-prev').addEventListener('click', function() {
                previousStep();
            });

            document.getElementById('btn-next').addEventListener('click', function() {
                nextStep();
            });

            document.getElementById('btn-submit').addEventListener('click', function() {
                submitTransaksi();
            });
        }

        function filterTim(searchTerm) {
            const items = document.querySelectorAll('.tim-item');
            items.forEach(item => {
                const nama = item.dataset.nama.toLowerCase();
                if (nama.includes(searchTerm.toLowerCase())) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function selectTim(element) {
            // Remove previous selection
            document.querySelectorAll('.tim-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Add selection
            element.classList.add('selected');
            
            selectedData.peserta_id = element.dataset.id;
            selectedData.nama_tim = element.dataset.nama;
            selectedData.saldo = element.dataset.saldo;
            
            // Load soal if jenis already selected
            if (selectedData.jenis) {
                loadSoalByPeserta();
            }
            
            document.getElementById('btn-next').disabled = false;
        }

        function selectAksi(aksi) {
            selectedData.jenis = aksi;
            
            // Update UI
            document.querySelectorAll('.aksi-item').forEach(item => {
                item.classList.remove('selected');
            });
            document.querySelector(`[data-aksi="${aksi}"]`).classList.add('selected');
            
            // Load soal berdasarkan peserta dan jenis transaksi
            if (selectedData.peserta_id) {
                loadSoalByPeserta();
            }
            
            document.getElementById('btn-next').disabled = false;
        }

        function loadSoalByPeserta() {
            if (!selectedData.peserta_id || !selectedData.jenis) return;
            
            console.log('Loading soal for peserta_id:', selectedData.peserta_id, 'jenis:', selectedData.jenis);
            
            // Show loading state
            if (selectedData.jenis === 'beli') {
                document.getElementById('soal-beli').innerHTML = '<option value="">Loading...</option>';
            } else {
                document.getElementById('soal-jual').innerHTML = '<option value="">Loading...</option>';
            }
            
            const url = `{{ route("public.soal.by.peserta") }}?peserta_id=${selectedData.peserta_id}&jenis=${selectedData.jenis}`;
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    updateSoalOptions(data.soals);
                } else {
                    console.error('Error loading soal:', data.message);
                    // Show error message
                    const selectId = selectedData.jenis === 'beli' ? 'soal-beli' : 'soal-jual';
                    document.getElementById(selectId).innerHTML = '<option value="">Error loading soal</option>';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                // Show error message
                const selectId = selectedData.jenis === 'beli' ? 'soal-beli' : 'soal-jual';
                document.getElementById(selectId).innerHTML = '<option value="">Error loading soal</option>';
            });
        }

        function updateSoalOptions(soals) {
            const selectId = selectedData.jenis === 'beli' ? 'soal-beli' : 'soal-jual';
            const select = document.getElementById(selectId);
            
            select.innerHTML = '<option value="">-- Pilih Soal --</option>';
            
            console.log('Updating soal options:', soals);
            
            soals.forEach(soal => {
                console.log('Processing soal:', soal);
                const option = document.createElement('option');
                option.value = soal.kode_soal;
                
                if (selectedData.jenis === 'beli') {
                    option.dataset.harga = soal.harga_beli;
                    option.textContent = `${soal.kode_soal} - ${soal.mapel} (Rp ${new Intl.NumberFormat('id-ID').format(soal.harga_beli)})`;
                } else {
                    option.dataset.benar = soal.harga_benar;
                    option.dataset.salah = soal.harga_salah;
                    option.dataset.tipe = soal.tipe_soal;
                    option.textContent = `${soal.kode_soal} - ${soal.mapel} - ${soal.tipe_soal}`;
                    
                    // Add pilihan jawaban if available
                    if (soal.pilihan_jawaban) {
                        console.log('Raw pilihan jawaban:', soal.pilihan_jawaban, typeof soal.pilihan_jawaban);
                        try {
                            let pilihan;
                            if (typeof soal.pilihan_jawaban === 'string') {
                                pilihan = JSON.parse(soal.pilihan_jawaban);
                            } else {
                                pilihan = soal.pilihan_jawaban;
                            }
                            console.log('Parsed pilihan:', pilihan);
                            console.log('Is array?', Array.isArray(pilihan));
                            option.dataset.pilihan = JSON.stringify(pilihan);
                        } catch (e) {
                            console.error('Error parsing pilihan jawaban:', e);
                        }
                    }
                }
                
                select.appendChild(option);
            });
            
            // Show appropriate section for step 3
            if (selectedData.jenis === 'beli') {
                document.getElementById('beli-section').classList.remove('hidden');
                document.getElementById('jual-section').classList.add('hidden');
            } else {
                document.getElementById('jual-section').classList.remove('hidden');
                document.getElementById('beli-section').classList.add('hidden');
            }
        }

        function updateHargaBeli() {
            const select = document.getElementById('soal-beli');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                const harga = option.dataset.harga;
                document.getElementById('harga-beli-text').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga);
                document.getElementById('harga-beli-info').classList.remove('hidden');
                selectedData.kode_soal = option.value;
                selectedData.harga = harga;
            } else {
                document.getElementById('harga-beli-info').classList.add('hidden');
                delete selectedData.kode_soal;
                delete selectedData.harga;
            }
        }

        function updateHargaJual() {
            const select = document.getElementById('soal-jual');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                const hargaBenar = option.dataset.benar;
                const hargaSalah = option.dataset.salah;
                document.getElementById('harga-benar-text').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaBenar);
                document.getElementById('harga-salah-text').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaSalah);
                document.getElementById('harga-jual-info').classList.remove('hidden');
                selectedData.kode_soal = option.value;
                selectedData.harga_benar = hargaBenar;
                selectedData.harga_salah = hargaSalah;
                selectedData.tipe_soal = option.dataset.tipe;
            } else {
                document.getElementById('harga-jual-info').classList.add('hidden');
                delete selectedData.kode_soal;
                delete selectedData.harga_benar;
                delete selectedData.harga_salah;
                delete selectedData.tipe_soal;
            }
        }

        function setupJawabanInput() {
            const select = document.getElementById('soal-jual');
            const option = select.options[select.selectedIndex];
            
            // Hide all jawaban inputs
            document.getElementById('jawaban-container').classList.add('hidden');
            document.getElementById('jawaban-pilgan').classList.add('hidden');
            document.getElementById('jawaban-kompleks').classList.add('hidden');
            document.getElementById('jawaban-essai').classList.add('hidden');
            
            // Reset selections
            document.querySelectorAll('.jawaban-option').forEach(btn => {
                btn.classList.remove('selected');
            });
            
            document.querySelectorAll('.jawaban-kompleks-option').forEach(btn => {
                btn.classList.remove('selected');
            });
            
            document.getElementById('jawaban-text').value = '';
            document.getElementById('jawaban').value = '';
            document.getElementById('selected-kompleks').textContent = '';
            
            if (option.value) {
                const tipe = option.dataset.tipe;
                document.getElementById('jawaban-container').classList.remove('hidden');
                
                if (tipe === 'Pilihan Ganda') {
                    document.getElementById('jawaban-pilgan').classList.remove('hidden');
                    
                    // Update pilihan jawaban dinamis
                    if (option.dataset.pilihan) {
                        try {
                            const pilihan = JSON.parse(option.dataset.pilihan);
                            const container = document.querySelector('#jawaban-pilgan .grid');
                            container.innerHTML = '';
                            
                            // Handle both array format and object format
                            if (Array.isArray(pilihan)) {
                                // Array format: [{huruf: "A", teks: "text"}, ...]
                                pilihan.forEach(item => {
                                    const button = document.createElement('button');
                                    button.type = 'button';
                                    button.className = 'jawaban-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 px-4 py-3 rounded-xl transition-all text-left shadow-sm';
                                    button.dataset.value = item.huruf;
                                    button.innerHTML = `<span class="font-bold">${item.huruf}:</span> ${item.teks}`;
                                    
                                    button.addEventListener('click', function() {
                                        document.querySelectorAll('.jawaban-option').forEach(btn => {
                                            btn.classList.remove('selected');
                                        });
                                        
                                        this.classList.add('selected');
                                        
                                        document.getElementById('jawaban').value = this.dataset.value;
                                        checkStep3Validity();
                                    });
                                    
                                    container.appendChild(button);
                                });
                            } else {
                                // Object format: {A: "text", B: "text", ...}
                                Object.entries(pilihan).forEach(([key, value]) => {
                                    const button = document.createElement('button');
                                    button.type = 'button';
                                    button.className = 'jawaban-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 px-4 py-3 rounded-xl transition-all text-left shadow-sm';
                                    button.dataset.value = key;
                                    button.innerHTML = `<span class="font-bold">${key.toUpperCase()}:</span> ${value}`;
                                    
                                    button.addEventListener('click', function() {
                                        document.querySelectorAll('.jawaban-option').forEach(btn => {
                                            btn.classList.remove('selected');
                                        });
                                        
                                        this.classList.add('selected');
                                        
                                        document.getElementById('jawaban').value = this.dataset.value;
                                        checkStep3Validity();
                                    });
                                    
                                    container.appendChild(button);
                                });
                            }
                        } catch (e) {
                            console.error('Error parsing pilihan jawaban:', e);
                        }
                    }
                } else if (tipe === 'Pilihan Ganda Kompleks') {
                    document.getElementById('jawaban-kompleks').classList.remove('hidden');
                    
                    // Update pilihan jawaban kompleks dinamis
                    if (option.dataset.pilihan) {
                        try {
                            const pilihan = JSON.parse(option.dataset.pilihan);
                            const container = document.querySelector('#jawaban-kompleks .grid');
                            container.innerHTML = '';
                            
                            // Handle both array format and object format
                            if (Array.isArray(pilihan)) {
                                // Array format: [{huruf: "A", teks: "text"}, ...]
                                pilihan.forEach(item => {
                                    const button = document.createElement('button');
                                    button.type = 'button';
                                    button.className = 'jawaban-kompleks-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 px-4 py-2 rounded-xl transition-all text-left shadow-sm';
                                    button.dataset.value = item.huruf;
                                    button.innerHTML = `<span class="font-bold">${item.huruf}:</span> ${item.teks}`;
                                    
                                    button.addEventListener('click', function() {
                                        this.classList.toggle('selected');
                                        
                                        updateKompleksSelection();
                                        checkStep3Validity();
                                    });
                                    
                                    container.appendChild(button);
                                });
                            } else {
                                // Object format: {A: "text", B: "text", ...}
                                Object.entries(pilihan).forEach(([key, value]) => {
                                    const button = document.createElement('button');
                                    button.type = 'button';
                                    button.className = 'jawaban-kompleks-option bg-white hover:bg-gray-50 border-2 border-gray-900 hover:border-gray-700 px-4 py-2 rounded-xl transition-all text-left shadow-sm';
                                    button.dataset.value = key;
                                    button.innerHTML = `<span class="font-bold">${key}:</span> ${value}`;
                                    
                                    button.addEventListener('click', function() {
                                        this.classList.toggle('selected');
                                        
                                        updateKompleksSelection();
                                        checkStep3Validity();
                                    });
                                    
                                    container.appendChild(button);
                                });
                            }
                        } catch (e) {
                            console.error('Error parsing pilihan jawaban kompleks:', e);
                        }
                    }
                } else if (tipe === 'Essai') {
                    document.getElementById('jawaban-essai').classList.remove('hidden');
                }
            }
        }

        function updateKompleksSelection() {
            const selected = [];
            document.querySelectorAll('.jawaban-kompleks-option').forEach(btn => {
                if (btn.classList.contains('selected')) {
                    selected.push(btn.dataset.value);
                }
            });
            
            document.getElementById('selected-kompleks').textContent = selected.join(', ');
            document.getElementById('jawaban').value = selected.join(',');
        }

        function checkStep3Validity() {
            let isValid = false;
            
            if (selectedData.jenis === 'beli') {
                isValid = selectedData.kode_soal && selectedData.harga;
            } else if (selectedData.jenis === 'jual') {
                const jawaban = document.getElementById('jawaban').value.trim();
                isValid = selectedData.kode_soal && jawaban;
                if (jawaban) {
                    selectedData.jawaban = jawaban;
                }
            }
            
            document.getElementById('btn-next').disabled = !isValid;
        }

        function updateKonfirmasi() {
            const container = document.getElementById('konfirmasi-detail');
            let html = `
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tim:</span>
                        <span class="text-gray-900 font-semibold">${selectedData.nama_tim}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Saldo Saat Ini:</span>
                        <span class="text-gray-900 font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(selectedData.saldo)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Aksi:</span>
                        <span class="text-gray-900 font-semibold uppercase">${selectedData.jenis}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Soal:</span>
                        <span class="text-gray-900 font-semibold">${selectedData.kode_soal}</span>
                    </div>
            `;
            
            if (selectedData.tipe_soal) {
                html += `
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tipe Soal:</span>
                        <span class="text-gray-900 font-semibold">${selectedData.tipe_soal}</span>
                    </div>
                `;
            }
            
            if (selectedData.jenis === 'beli') {
                html += `
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga:</span>
                        <span class="text-red-600 font-semibold">-Rp ${new Intl.NumberFormat('id-ID').format(selectedData.harga)}</span>
                    </div>
                `;
            } else {
                let jawabanDisplay = selectedData.jawaban;
                if (selectedData.tipe_soal === 'Pilihan Ganda Kompleks') {
                    jawabanDisplay = selectedData.jawaban.split(',').join(', ');
                }
                
                html += `
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jawaban:</span>
                        <span class="text-gray-900 font-semibold">${jawabanDisplay}</span>
                    </div>
                    <div class="border-t border-white/20 pt-3">
                        <div class="flex justify-between">
                            <span class="text-gray-300">Jika Benar:</span>
                            <span class="text-green-300 font-semibold">+Rp ${new Intl.NumberFormat('id-ID').format(selectedData.harga_benar)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Jika Salah:</span>
                            <span class="text-red-300 font-semibold">+Rp ${new Intl.NumberFormat('id-ID').format(selectedData.harga_salah)}</span>
                        </div>
                    </div>
                `;
            }
            
            html += '</div>';
            container.innerHTML = html;
        }

        function nextStep() {
            if (currentStep < 4) {
                currentStep++;
                updateStepUI();
                
                if (currentStep === 3) {
                    // Show appropriate section
                    if (selectedData.jenis === 'beli') {
                        document.getElementById('beli-section').classList.remove('hidden');
                        document.getElementById('jual-section').classList.add('hidden');
                    } else {
                        document.getElementById('beli-section').classList.add('hidden');
                        document.getElementById('jual-section').classList.remove('hidden');
                    }
                    document.getElementById('btn-next').disabled = true;
                } else if (currentStep === 4) {
                    updateKonfirmasi();
                }
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                currentStep--;
                updateStepUI();
            }
        }

        function updateStepUI() {
            // Hide all step contents
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show current step content
            document.getElementById(`step-content-${currentStep}`).classList.remove('hidden');
            
            // Update step indicators
            for (let i = 1; i <= 4; i++) {
                const indicator = document.getElementById(`step-${i}`);
                if (i <= currentStep) {
                    indicator.classList.add('bg-gray-900', 'border-gray-900', 'text-white');
                    indicator.classList.remove('bg-gray-300', 'border-gray-300', 'text-gray-600');
                } else {
                    indicator.classList.add('bg-gray-300', 'border-gray-300', 'text-gray-600');
                    indicator.classList.remove('bg-gray-900', 'border-gray-900', 'text-white');
                }
            }
            
            // Update navigation buttons
            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');
            const btnSubmit = document.getElementById('btn-submit');
            
            if (currentStep === 1) {
                btnPrev.classList.add('hidden');
                btnNext.classList.remove('hidden');
                btnSubmit.classList.add('hidden');
                btnNext.disabled = !selectedData.peserta_id;
            } else if (currentStep === 4) {
                btnPrev.classList.remove('hidden');
                btnNext.classList.add('hidden');
                btnSubmit.classList.remove('hidden');
            } else {
                btnPrev.classList.remove('hidden');
                btnNext.classList.remove('hidden');
                btnSubmit.classList.add('hidden');
                
                if (currentStep === 2) {
                    btnNext.disabled = !selectedData.jenis;
                } else if (currentStep === 3) {
                    checkStep3Validity();
                }
            }
        }

        function submitTransaksi() {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('peserta_id', selectedData.peserta_id);
            formData.append('kode_soal', selectedData.kode_soal);
            formData.append('jenis', selectedData.jenis);
            
            if (selectedData.jenis === 'jual') {
                formData.append('jawaban', selectedData.jawaban);
            }
            
            fetch('{{ route("public.transaksi.process") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showFeedback(true, data.feedback, data.sound);
                    
                    // Play sound
                    if (data.sound) {
                        const audio = document.getElementById(data.sound + '-sound');
                        if (audio) {
                            audio.play().catch(e => console.log('Audio play failed:', e));
                        }
                    }
                    
                    // Reset form after 3 seconds
                    setTimeout(() => {
                        resetForm();
                    }, 3000);
                } else {
                    showFeedback(false, 'Terjadi kesalahan saat memproses transaksi.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showFeedback(false, 'Terjadi kesalahan koneksi.');
            });
        }

        function showFeedback(success, message, sound = null) {
            const modal = document.getElementById('feedback-modal');
            const icon = document.getElementById('feedback-icon');
            const title = document.getElementById('feedback-title');
            const messageEl = document.getElementById('feedback-message');
            
            if (success) {
                icon.className = 'w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center bg-green-500';
                icon.innerHTML = '<i class="fas fa-check text-white text-3xl"></i>';
                title.textContent = 'Transaksi Berhasil!';
                title.className = 'text-2xl font-bold text-green-300 mb-4';
            } else {
                icon.className = 'w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center bg-red-500';
                icon.innerHTML = '<i class="fas fa-times text-white text-3xl"></i>';
                title.textContent = 'Transaksi Gagal!';
                title.className = 'text-2xl font-bold text-red-300 mb-4';
            }
            
            messageEl.textContent = message;
            modal.classList.remove('hidden');
        }

        function closeFeedbackModal() {
            document.getElementById('feedback-modal').classList.add('hidden');
        }

        function resetForm() {
            currentStep = 1;
            selectedData = {};
            
            // Reset UI
            document.querySelectorAll('.tim-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            document.querySelectorAll('.aksi-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            document.getElementById('soal-beli').value = '';
            document.getElementById('soal-jual').value = '';
            document.getElementById('jawaban-text').value = '';
            document.getElementById('jawaban').value = '';
            document.getElementById('search-tim').value = '';
            
            // Reset jawaban options
            document.querySelectorAll('.jawaban-option').forEach(btn => {
                btn.classList.remove('selected');
            });
            
            document.querySelectorAll('.jawaban-kompleks-option').forEach(btn => {
                btn.classList.remove('selected');
            });
            
            document.getElementById('selected-kompleks').textContent = '';
            
            document.getElementById('harga-beli-info').classList.add('hidden');
            document.getElementById('harga-jual-info').classList.add('hidden');
            document.getElementById('jawaban-container').classList.add('hidden');
            document.getElementById('jawaban-pilgan').classList.add('hidden');
            document.getElementById('jawaban-kompleks').classList.add('hidden');
            document.getElementById('jawaban-essai').classList.add('hidden');
            
            filterTim(''); // Show all teams
            updateStepUI();
        }
    </script>
</body>
</html>
