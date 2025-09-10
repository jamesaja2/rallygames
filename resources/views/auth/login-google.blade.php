<!DOCTYPE html>
<html>
<head>
    <title>Login with Google</title>
    @vite('resources/css/app.css') {{-- Hanya jika pakai Vite --}}
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-6 bg-white rounded-xl shadow">
        <h2 class="text-center text-xl font-bold mb-6">Login with Google</h2>

        <a
            href="{{ url('/auth/google/redirect') }}"
            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg shadow hover:bg-red-700 transition"
        >
            <svg class="w-5 h-5" viewBox="0 0 48 48"><path fill="#fff" d="M..."/></svg>
            Login with Google
        </a>
    </div>
</body>
</html>
