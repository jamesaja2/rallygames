<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rally Games - Password Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md border border-gray-200">
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-xl bg-gray-100 mx-auto mb-4 flex items-center justify-center border border-gray-200">
                <img src="{{ asset('logo.png') }}" alt="Rally Games Logo" class="w-12 h-12 object-contain">
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Rally Games</h1>
            <p class="text-gray-600">Masukkan password untuk mengakses sistem</p>
        </div>

        <form action="{{ route('public.password.check') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Masukkan password..."
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                    required
                    autofocus
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button 
                type="submit" 
                class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-xl transition duration-300 shadow-sm"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-500 text-sm">© 2025 SMAK St. Louis 1 Surabaya IT Department</p>
        </div>
    </div>
</body>
</html>
