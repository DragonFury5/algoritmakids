<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — AlgoritmaKids</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-kid-bg font-body text-kid-text antialiased min-h-screen flex items-center justify-center p-6">

    <div class="text-center max-w-md">
        {{-- Big Icon --}}
        <div class="w-32 h-32 rounded-full bg-kid-coral/10 flex items-center justify-center mx-auto mb-6 animate-pulse-soft">
            <x-lucide-ghost class="w-16 h-16 text-kid-coral" stroke-width="1.5" />
        </div>

        {{-- Text --}}
        <h1 class="text-6xl font-extrabold text-kid-text mb-2">404</h1>
        <h2 class="text-2xl font-extrabold mb-3">
            {{ app()->getLocale() === 'id' ? 'Waduh, Tersesat!' : 'Oops, Lost!' }}
        </h2>
        <p class="text-gray-500 mb-8">
            {{ app()->getLocale() === 'id' 
                ? 'Sepertinya robot kita tidak bisa menemukan halaman yang kamu cari.' 
                : 'It seems our robot can\'t find the page you are looking for.' }}
        </p>

        {{-- Button --}}
        <a href="{{ route('home') }}" class="btn-kid bg-kid-blue text-white w-full justify-center text-lg gap-2">
            <x-lucide-home class="w-5 h-5" stroke-width="2.5" />
            <span>{{ app()->getLocale() === 'id' ? 'Kembali ke Beranda' : 'Back to Home' }}</span>
        </a>
    </div>

</body>
</html>