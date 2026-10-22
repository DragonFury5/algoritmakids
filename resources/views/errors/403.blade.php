<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — AlgoritmaKids</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* 1. Neutral eyes pop in */
        @keyframes eyes-appear {
            0%, 15% { opacity: 0; transform: scale(0.8); }
            20%, 100% { opacity: 1; transform: scale(1); }
        }

        /* 2. Blank straight mouth appears */
        @keyframes blank-mouth {
            0%, 20% { opacity: 0; }
            25%, 35% { opacity: 1; }
            40%, 100% { opacity: 0; }
        }

        /* 3. Frown appears over the blank mouth */
        @keyframes frown-mouth {
            0%, 35% { opacity: 0; }
            40%, 100% { opacity: 1; }
        }

        /* 4. Hand waves 'No' */
        @keyframes wave-hand {
            0%, 45% { opacity: 0; transform: translateY(20px) rotate(0deg); }
            50% { opacity: 1; transform: translateY(0px) rotate(-15deg); }
            60% { transform: translateY(0px) rotate(15deg); }
            70% { transform: translateY(0px) rotate(-15deg); }
            80% { transform: translateY(0px) rotate(15deg); }
            85%, 100% { opacity: 0; transform: translateY(20px) rotate(0); }
        }

        /* 5. Head shakes left and right */
        @keyframes head-shake {
            0%, 45% { transform: rotate(0deg); }
            50% { transform: rotate(10deg); }
            55% { transform: rotate(-10deg); }
            60% { transform: rotate(10deg); }
            65% { transform: rotate(-10deg); }
            70%, 100% { transform: rotate(0deg); }
        }

        /* Pupils dart left and right when frowning */
        @keyframes eye-look {
            0%, 40% { transform: translateX(0); }
            50%, 70% { transform: translateX(-3px); }
            80%, 100% { transform: translateX(3px); }
        }
    </style>
</head>
<body class="bg-kid-bg font-body text-kid-text antialiased min-h-screen flex items-center justify-center p-6">

    <div class="text-center max-w-md">
        
        {{-- Animated Icon Container --}}
        <div class="relative w-32 h-32 rounded-full bg-kid-coral/10 flex items-center justify-center mx-auto mb-6">
            
                      {{-- Waving Pointing Finger (Brought to front, moved left) --}}
            <div class="absolute z-20 top-1/2 -translate-y-1/2 -right-4 text-4xl origin-bottom" style="transform: scaleX(-1);">
                <span class="inline-block" style="animation: wave-hand 5s infinite ease-in-out;">
                    ☝️
                </span>
            </div>

            {{-- Robot Shaking Head --}}
            <div style="animation: head-shake 5s infinite ease-in-out;">
                <svg viewBox="0 0 100 100" class="w-20 h-20">
                    {{-- Antenna --}}
                    <line x1="50" y1="30" x2="50" y2="15" stroke="#2D2A26" stroke-width="2.5"/>
                    <circle cx="50" cy="15" r="4" fill="#FF6B6B"/>
                    
                    {{-- Body --}}
                    <rect x="25" y="30" width="50" height="45" rx="12" fill="#4ECDC4" stroke="#2D2A26" stroke-width="2.5"/>
                    
                    {{-- Screen --}}
                    <rect x="32" y="38" width="36" height="26" rx="4" fill="#2D2A26"/>

                    {{-- Eyes Group --}}
                    <g style="animation: eyes-appear 5s infinite ease-in-out;">
                        <!-- Left Eye -->
                        <circle cx="42" cy="48" r="4.5" fill="white" />
                        <circle cx="42" cy="48" r="2" fill="#2D2A26" style="animation: eye-look 5s infinite ease-in-out;" />
                        
                        <!-- Right Eye -->
                        <circle cx="58" cy="48" r="4.5" fill="white" />
                        <circle cx="58" cy="48" r="2" fill="#2D2A26" style="animation: eye-look 5s infinite ease-in-out;" />
                    </g>
                    
                    {{-- Blank Straight Mouth --}}
                    <line x1="44" y1="56" x2="56" y2="56" stroke="white" stroke-width="2.5" stroke-linecap="round" 
                          style="animation: blank-mouth 5s infinite ease-in-out;" />

                    {{-- Frown Mouth --}}
                    <path d="M 44 57 Q 50 52 56 57" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" 
                          style="animation: frown-mouth 5s infinite ease-in-out;" />
                </svg>
            </div>
            
        </div>

        {{-- Text --}}
        <h1 class="text-6xl font-extrabold text-kid-text mb-2">403</h1>
        <h2 class="text-2xl font-extrabold mb-3">
            {{ app()->getLocale() === 'id' ? 'Akses Ditolak!' : 'Access Denied!' }}
        </h2>
        <p class="text-gray-500 mb-8">
            {{ app()->getLocale() === 'id' 
                ? 'Sepertinya robot penjaga tidak mengizinkanmu masuk ke halaman ini.' 
                : 'It seems the guard robot won\'t let you enter this page.' }}
        </p>

        {{-- Button --}}
        <a href="{{ route('home') }}" class="btn-kid bg-kid-blue text-white w-full justify-center text-lg gap-2">
            <x-lucide-home class="w-5 h-5" stroke-width="2.5" />
            <span>{{ app()->getLocale() === 'id' ? 'Kembali ke Beranda' : 'Back to Home' }}</span>
        </a>
    </div>

</body>
</html>