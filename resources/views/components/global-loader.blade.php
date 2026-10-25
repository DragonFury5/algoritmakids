<div
    x-data="{
        tips: [
            '{{ app()->getLocale() === 'id' ? 'Bersiap-siap...' : 'Getting things ready...' }}',
            '{{ app()->getLocale() === 'id' ? 'Sudahkah kamu berdoa hari ini?' : 'Have you prayed today?' }}',
            '{{ app()->getLocale() === 'id' ? 'Katakan pada ibumu bahwa kamu menyayanginya!' : 'Tell your mom that you love her!' }}',
            '{{ app()->getLocale() === 'id' ? 'Sambil menunggu, minum air putih dulu yuk.' : 'While waiting, go drink some water.' }}',
            '{{ app()->getLocale() === 'id' ? 'Jangan lupa istirahat sejenak.' : 'Remember to take a quick stretch.' }}',
            '{{ app()->getLocale() === 'id' ? 'Hampir selesai!' : 'Almost there!' }}',
        ],
        currentTip: '',
        interval: null,
        init() {
            this.currentTip = this.tips[0];
            this.interval = setInterval(() => {
                if (! this.$store.loader.visible) {
                    return;
                }

                this.currentTip = this.tips[(this.tips.indexOf(this.currentTip) + 1) % this.tips.length];
            }, 3500);
        },
        destroy() {
            clearInterval(this.interval);
        },
    }"
    x-show="$store.loader.visible"
    x-cloak
    x-transition:enter="transition-opacity duration-200 ease-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity duration-150 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-kid-bg/90 backdrop-blur-sm"
    role="status"
    aria-live="polite"
    aria-busy="true"
>
    <style>
        @keyframes loader-run { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
        @keyframes loader-jump { 0%, 30%, 70%, 100% { transform: translateY(0); } 40%, 60% { transform: translateY(-35px); } }
        @keyframes loader-wind { 0% { transform: translateX(100px); opacity: 0; } 10%, 90% { opacity: 1; } 100% { transform: translateX(-100px); opacity: 0; } }
        @keyframes loader-rock { 0% { transform: translateX(120px); } 100% { transform: translateX(-120px); } }
        @keyframes loader-bar { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }

        .animate-loader-run { animation: loader-run 0.6s infinite ease-in-out; }
        .animate-loader-jump { animation: loader-jump 3s infinite ease-in-out; }
        .animate-loader-wind { animation: loader-wind 1s infinite linear; }
        .animate-loader-rock { animation: loader-rock 3s infinite linear; }
        .animate-loader-bar { animation: loader-bar 1.5s infinite ease-in-out; }
    </style>

    <div class="mb-12 h-8 text-center">
        <p class="animate-pulse text-lg font-bold text-kid-text md:text-xl" x-text="currentTip"></p>
    </div>

    <div class="relative flex h-32 w-64 items-end justify-center overflow-hidden">
        <div class="absolute top-10 left-0 flex w-full flex-col gap-2 opacity-50">
            <div class="animate-loader-wind h-0.5 w-12 rounded bg-kid-blue"></div>
            <div class="animate-loader-wind ml-8 h-0.5 w-16 rounded bg-kid-blue" style="animation-delay: 0.3s"></div>
            <div class="animate-loader-wind ml-20 h-0.5 w-8 rounded bg-kid-blue" style="animation-delay: 0.6s"></div>
        </div>

        <div class="animate-loader-rock absolute bottom-4 left-1/2 -ml-4 h-4 w-8 rounded-t-full bg-gray-400"></div>

        <div class="animate-loader-jump relative z-10 mb-4">
            <svg viewBox="0 0 100 100" class="animate-loader-run h-24 w-24 drop-shadow-lg" aria-hidden="true">
                <line x1="50" y1="30" x2="50" y2="15" stroke="#2D2A26" stroke-width="3" stroke-linecap="round" />
                <circle cx="50" cy="15" r="5" fill="#FF6B6B" />
                <rect x="25" y="30" width="50" height="45" rx="12" fill="#4ECDC4" stroke="#2D2A26" stroke-width="3" />
                <rect x="32" y="38" width="36" height="26" rx="4" fill="#2D2A26" />
                <circle cx="42" cy="48" r="4" fill="#FFF8F0" />
                <circle cx="58" cy="48" r="4" fill="#FFF8F0" />
                <path d="M 44 56 Q 50 60 56 56" stroke="#FFF8F0" stroke-width="2.5" fill="none" stroke-linecap="round" />
                <rect x="20" y="45" width="5" height="15" rx="2.5" fill="#4ECDC4" />
                <rect x="75" y="45" width="5" height="15" rx="2.5" fill="#4ECDC4" />
            </svg>
        </div>

        <div class="absolute bottom-4 h-1 w-full rounded-full bg-kid-text/20"></div>
    </div>

    <div class="relative mt-6 h-3 w-64 overflow-hidden rounded-full border border-gray-300 bg-gray-200">
        <div class="animate-loader-bar absolute h-full w-1/2 rounded-full bg-kid-blue"></div>
    </div>
</div>
