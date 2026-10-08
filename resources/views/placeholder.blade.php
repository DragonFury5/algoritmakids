<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>Coming soon</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-kid-bg font-kid text-kid-text p-10">
    <h1 class="text-3xl font-extrabold">Dashboard coming soon…</h1>
    <p class="mt-4">Logged in as: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->role }})</p>
    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button class="btn-kid bg-kid-teal">Log out</button>
    </form>
</body>
</html>