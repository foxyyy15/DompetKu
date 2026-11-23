@props([
    'title' => 'DompetKu',
    'showHeaderBrandText' => false,
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — DompetKu</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-backdrop min-h-screen text-slate-900 antialiased">
    <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-5 py-6 sm:px-8">
        {{-- Centered Card Content --}}
        <main class="flex flex-1 items-center justify-center py-8">
            {{ $slot }}
        </main>
    </div>

    <script>
        function togglePasswordField(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.classList.toggle('text-emerald-700', isPassword);
        }
    </script>
</body>
</html>
