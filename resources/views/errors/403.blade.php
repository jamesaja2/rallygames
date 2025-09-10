<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - SHSC 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md border border-gray-200 text-center">
        <div class="w-20 h-20 rounded-xl bg-red-50 mx-auto mb-6 flex items-center justify-center border border-red-200">
            <i class="fas fa-ban text-red-500 text-3xl"></i>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Akses Ditolak</h1>
        
        <div class="mb-6">
            <p class="text-gray-600 mb-4">{{ $exception->getMessage() ?: 'Dashboard sedang tidak dapat diakses.' }}</p>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                    <p class="text-yellow-800 text-sm">
                        Silakan hubungi panitia untuk informasi lebih lanjut.
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <button onclick="window.location.reload()" 
                    class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-xl transition duration-300 shadow-sm">
                <i class="fas fa-sync-alt mr-2"></i>
                Coba Lagi
            </button>
            
            <button onclick="window.history.back()" 
                    class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </button>
        </div>

        <div class="mt-8 text-center">
            <p class="text-gray-500 text-sm">© 2025 SMAK St. Louis 1 Surabaya</p>
            <p class="text-gray-400 text-xs mt-1">IT Department</p>
        </div>
    </div>

    <script>
        // Auto refresh setiap 30 detik
        setTimeout(() => {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>
