<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ app()->getLocale() === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-kid-bg font-kid text-kid-text">
    <div class="max-w-3xl mx-auto py-12 px-6">
        <a href="{{ route('home') }}" class="text-kid-blue font-bold">← {{ app()->getLocale() === 'id' ? 'Kembali' : 'Back' }}</a>
        <h1 class="text-3xl font-extrabold mt-6 mb-4">
            {{ app()->getLocale() === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy' }}
        </h1>
        <div class="prose prose-lg max-w-none bg-white rounded-kid shadow p-8">
            @if(app()->getLocale() === 'id')
                <p><strong>Terakhir diperbarui:</strong> {{ now()->format('d M Y') }}</p>
                <h2>Data yang Kami Kumpulkan</h2>
                <p>Kami mengumpulkan nama, email, avatar, dan progres belajar anak. Tidak ada data lain yang dikumpulkan.</p>
                <h2>Siapa yang Dapat Melihat</h2>
                <p>Hanya orang tua/wali (akun Anda) dan administrator platform.</p>
                <h2>Iklan & Pelacakan</h2>
                <p>Tidak ada iklan. Tidak ada pelacakan pihak ketiga.</p>
                <h2>Penyimpanan Data</h2>
                <p>Data disimpan di server lokal / universitas.</p>
                <h2>Hak Anda</h2>
                <p>Anda dapat meminta penghapusan data kapan saja dengan menghubungi administrator.</p>
                <h2>Persetujuan</h2>
                <p>Dengan membuat akun, Anda menyatakan bahwa Anda adalah orang tua/wali dan menyetujui anak Anda menggunakan platform ini.</p>
            @else
                <p><strong>Last updated:</strong> {{ now()->format('d M Y') }}</p>
                <h2>Data We Collect</h2>
                <p>We collect the child's name, email, avatar, and learning progress. No other data is collected.</p>
                <h2>Who Can See It</h2>
                <p>Only the parent/guardian (your account) and the platform administrator.</p>
                <h2>Ads & Tracking</h2>
                <p>No ads. No third-party tracking.</p>
                <h2>Data Storage</h2>
                <p>Data is stored on a local / university server.</p>
                <h2>Your Rights</h2>
                <p>You may request deletion of data at any time by contacting the administrator.</p>
                <h2>Consent</h2>
                <p>By creating an account, you confirm that you are the parent/guardian and consent to your child using this platform.</p>
            @endif
        </div>
    </div>
</body>
</html>