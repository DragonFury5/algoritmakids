<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ app()->getLocale() === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy' }} — AlgoritmaKids</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-kid-bg font-body text-kid-text antialiased">

    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold">
                <span class="text-kid-blue">Algoritma</span><span class="text-kid-mint">Kids</span>
            </a>
            <a href="{{ url()->previous() }}" class="text-sm font-bold text-kid-blue inline-flex items-center gap-1 hover:underline">
                <x-lucide-arrow-left class="w-4 h-4" stroke-width="2.5" />
                <span>{{ app()->getLocale() === 'id' ? 'Kembali' : 'Back' }}</span>
            </a>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-6 py-12 md:py-16">

        <div class="mb-10">
            <span class="inline-block bg-kid-blue/10 text-kid-blue font-bold text-xs uppercase tracking-wider px-4 py-1.5 rounded-full mb-4">
                {{ app()->getLocale() === 'id' ? 'Legal' : 'Legal' }}
            </span>
            <h1 class="text-3xl md:text-4xl font-extrabold mb-3">
                {{ app()->getLocale() === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy' }}
            </h1>
            <p class="text-gray-500 text-sm">
                {{ app()->getLocale() === 'id' ? 'Terakhir diperbarui:' : 'Last updated:' }}
                {{ now()->format('d M Y') }}
            </p>
        </div>

        <div class="bg-white rounded-kid border border-gray-200 p-6 md:p-10 prose prose-lg max-w-none
                    prose-headings:font-kid prose-headings:text-kid-text
                    prose-h2:mt-8 prose-h2:mb-3 prose-h2:text-xl
                    prose-p:text-gray-600 prose-p:leading-relaxed
                    prose-strong:text-kid-text">

            @if(app()->getLocale() === 'id')

                <p>
                    Kami di <strong>AlgoritmaKids</strong> berkomitmen melindungi privasi anak-anak dan orang tua.
                    Kebijakan ini menjelaskan data apa yang kami kumpulkan dan bagaimana kami menggunakannya.
                </p>

                <h2>1. Data yang Kami Kumpulkan</h2>
                <p>
                    Kami hanya mengumpulkan data minimum yang diperlukan untuk menjalankan platform:
                </p>
                <ul>
                    <li>Nama (orang tua dan anak)</li>
                    <li>Alamat email</li>
                    <li>Avatar (gambar pilihan)</li>
                    <li>Progres belajar (bintang, level yang diselesaikan)</li>
                    <li>Preferensi bahasa dan suara</li>
                </ul>

                <h2>2. Siapa yang Dapat Melihat Data</h2>
                <p>
                    Hanya <strong>orang tua/wali</strong> (akun Anda) dan <strong>administrator platform</strong>.
                    Kami tidak menjual atau membagikan data ke pihak ketiga.
                </p>

                <h2>3. Iklan & Pelacakan</h2>
                <p>
                    <strong>Tidak ada iklan.</strong>
                    <strong>Tidak ada pelacakan pihak ketiga.</strong>
                    Tidak ada cookie analitik eksternal.
                </p>

                <h2>4. Penyimpanan Data</h2>
                <p>
                    Data disimpan di server lokal/universitas yang aman. Kami tidak mengirim data ke luar negeri.
                </p>

                <h2>5. Hak Anda</h2>
                <p>
                    Anda dapat:
                </p>
                <ul>
                    <li>Meminta salinan data anak Anda</li>
                    <li>Meminta penghapusan data kapan saja</li>
                    <li>Menarik persetujuan dengan menghapus akun</li>
                </ul>
                <p>
                    Hubungi administrator platform untuk permintaan ini.
                </p>

                <h2>6. Persetujuan</h2>
                <p>
                    Dengan membuat akun, Anda menyatakan bahwa Anda adalah <strong>orang tua/wali</strong> dan
                    menyetujui anak Anda menggunakan platform ini. Persetujuan Anda dicatat dengan tanggal.
                </p>

                <h2>7. Perubahan Kebijakan</h2>
                <p>
                    Jika kami mengubah kebijakan ini, kami akan memberi tahu Anda melalui email atau notifikasi di platform.
                </p>

            @else

                <p>
                    At <strong>AlgoritmaKids</strong>, we are committed to protecting the privacy of children and parents.
                    This policy explains what data we collect and how we use it.
                </p>

                <h2>1. Data We Collect</h2>
                <p>
                    We collect only the minimum data needed to run the platform:
                </p>
                <ul>
                    <li>Name (parent and child)</li>
                    <li>Email address</li>
                    <li>Avatar (chosen image)</li>
                    <li>Learning progress (stars, completed levels)</li>
                    <li>Language and sound preferences</li>
                </ul>

                <h2>2. Who Can See It</h2>
                <p>
                    Only the <strong>parent/guardian</strong> (your account) and the <strong>platform administrator</strong>.
                    We never sell or share data with third parties.
                </p>

                <h2>3. Ads & Tracking</h2>
                <p>
                    <strong>No ads.</strong>
                    <strong>No third-party tracking.</strong>
                    No external analytics cookies.
                </p>

                <h2>4. Data Storage</h2>
                <p>
                    Data is stored on a secure local/university server. We do not send data abroad.
                </p>

                <h2>5. Your Rights</h2>
                <p>
                    You may:
                </p>
                <ul>
                    <li>Request a copy of your child's data</li>
                    <li>Request deletion at any time</li>
                    <li>Withdraw consent by deleting the account</li>
                </ul>
                <p>
                    Contact the platform administrator for these requests.
                </p>

                <h2>6. Consent</h2>
                <p>
                    By creating an account, you confirm that you are the <strong>parent/guardian</strong> and
                    consent to your child using this platform. Your consent is recorded with a timestamp.
                </p>

                <h2>7. Policy Changes</h2>
                <p>
                    If we change this policy, we will notify you via email or an in-platform notification.
                </p>

            @endif

        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('home') }}" class="btn-kid bg-kid-blue text-white">
                {{ app()->getLocale() === 'id' ? 'Kembali ke Beranda' : 'Back to Home' }}
            </a>
        </div>

    </main>

    <footer class="border-t border-gray-200 mt-8">
        <div class="max-w-6xl mx-auto px-6 py-8 text-sm text-gray-500 text-center">
            © {{ date('Y') }} AlgoritmaKids. {{ __('ui.made_with_love') }}
        </div>
    </footer>

    <x-global-loader />
</body>
</html>
