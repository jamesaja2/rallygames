<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rally Games - Password Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 min-h-screen flex items-center justify-center">
    <div class="bg-white/10 backdrop-blur-lg rounded-3xl shadow-2xl p-8 w-full max-w-md border border-white/20">
        <div class="text-center mb-8">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-lock text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Rally Games</h1>
            <p class="text-gray-300">Masukkan password untuk mengakses sistem</p>
        </div>

        <form action="{{ route('public.password.check') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Masukkan password..."
                    class="w-full px-4 py-3 bg-white/10 border border-white/30 rounded-xl text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent backdrop-blur-sm"
                    required
                    autofocus
                >
                @error('password')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition duration-300 transform hover:scale-105 shadow-lg"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-400 text-sm">© 2025 Rally Games System</p>
        </div>
    </div>
</body>
</html>
