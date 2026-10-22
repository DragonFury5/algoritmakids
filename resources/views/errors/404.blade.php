<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — AlgoritmaKids</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Ghost body slightly sways */
        @keyframes ghost-sway {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(4deg); }
            75% { transform: rotate(-4deg); }
        }

        /* Pupils dart right, then left */
        @keyframes eye-look {
            0%, 100% { transform: translateX(0); }
            20%, 35% { transform: translateX(4px); }
            55%, 70% { transform: translateX(-4px); }
        }

        /* The question mark pops in when the eyes look left */
        @keyframes float-q {
            0%, 45%, 100% { opacity: 0; transform: translateY(0) scale(0.8); }
            55% { opacity: 1; transform: translateY(-10px) scale(1.1); }
            75% { opacity: 1; transform: translateY(-15px) scale(1); }
            95% { opacity: 0; transform: translateY(-20px) scale(0.9); }
        }

        /* The blink: squishes the eyes quickly */
        @keyframes blink {
            0%, 90%, 100% { transform: scaleY(1); }
            95% { transform: scaleY(0.1); }
        }

        .eye-group {
            transform-origin: center;
            transform-box: fill-box;
            animation: blink 4s infinite;
        }
    </style>
</head>
<body class="bg-kid-bg font-body text-kid-text antialiased min-h-screen flex items-center justify-center p-6">

    <div class="text-center max-w-md">
        
        {{-- Animated Icon Container --}}
        <div class="relative w-32 h-32 rounded-full bg-kid-coral/10 flex items-center justify-center mx-auto mb-6">
            
            {{-- Floating Question Mark --}}
            <span class="absolute -top-4 right-2 text-5xl font-extrabold text-kid-coral" 
                  style="animation: float-q 4s infinite ease-in-out;">
                ?
            </span>

            {{-- Custom Ghost SVG --}}
            <div style="animation: ghost-sway 4s infinite ease-in-out;">
                <svg viewBox="0 0 100 100" class="w-20 h-20 text-kid-coral fill-current">
                    <!-- Ghost Body -->
                    <path d="M 20 40 C 20 10, 80 10, 80 40 L 80 85 L 70 75 L 60 85 L 50 75 L 40 85 L 30 75 L 20 85 Z" />
                    
                    <!-- Left Eye Group (Blinks) -->
                    <g class="eye-group">
                        <circle cx="38" cy="45" r="9" fill="white" />
                        <circle cx="38" cy="45" r="4" fill="#2D2A26" 
                                style="animation: eye-look 4s infinite ease-in-out;" />
                    </g>

                    <!-- Right Eye Group (Blinks) -->
                    <g class="eye-group">
                        <circle cx="62" cy="45" r="9" fill="white" />
                        <circle cx="62" cy="45" r="4" fill="#2D2A26" 
                                style="animation: eye-look 4s infinite ease-in-out;" />
                    </g>
                </svg>
            </div>
            
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