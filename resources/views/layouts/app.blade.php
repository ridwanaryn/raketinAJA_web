<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>raketinAJA | @yield('title', 'Booking Lapangan Olahraga')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col pb-32 md:pb-0">

    <!-- TopAppBar -->
    <header class="bg-[#deffe1]/70 backdrop-blur-xl fixed top-0 w-full z-50 shadow-[0_20px_40px_rgba(13,54,28,0.05)]">
        <div class="flex justify-between items-center w-full px-6 py-4 max-w-screen-2xl mx-auto">
            <div class="flex items-center gap-8">
                <a href="{{ route('fields.index') }}" class="text-2xl font-black italic tracking-tighter text-[#0052d0] headline-font select-none">raketinAJA</a>
                
                @auth
                    <nav class="hidden md:flex items-center gap-6">
                        @if(auth()->user()->role === 'owner')
                            <a class="font-headline font-bold text-lg tracking-tight py-1 {{ request()->routeIs('owner.dashboard') ? 'text-[#0052d0] border-b-4 border-[#0052d0]' : 'text-[#0d361c] opacity-60 hover:bg-[#bff5c8] px-3 rounded-lg transition-colors' }}" href="{{ route('owner.dashboard') }}">Dashboard</a>
                            <a class="font-headline font-bold text-lg tracking-tight py-1 {{ request()->routeIs('owner.bookings') ? 'text-[#0052d0] border-b-4 border-[#0052d0]' : 'text-[#0d361c] opacity-60 hover:bg-[#bff5c8] px-3 rounded-lg transition-colors' }}" href="{{ route('owner.dashboard') }}#recent-activity">Recent Activity</a>
                        @else
                            <a class="font-headline font-bold text-lg tracking-tight py-1 {{ request()->routeIs('fields.index') ? 'text-[#0052d0] border-b-4 border-[#0052d0]' : 'text-[#0d361c] opacity-60 hover:bg-[#bff5c8] px-3 rounded-lg transition-colors' }}" href="{{ route('fields.index') }}">Explore</a>
                            <a class="font-headline font-bold text-lg tracking-tight py-1 {{ request()->routeIs('bookings.index') ? 'text-[#0052d0] border-b-4 border-[#0052d0]' : 'text-[#0d361c] opacity-60 hover:bg-[#bff5c8] px-3 rounded-lg transition-colors' }}" href="{{ route('bookings.index') }}">Bookings</a>
                        @endif
                    </nav>
                @else
                    <nav class="hidden md:flex items-center gap-6">
                        <a class="font-headline font-bold text-lg tracking-tight py-1 text-[#0052d0] border-b-4 border-[#0052d0]" href="{{ route('fields.index') }}">Explore</a>
                    </nav>
                @endauth
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <div class="hidden lg:flex items-center text-sm font-semibold text-on-surface bg-surface-container/50 px-4 py-2 rounded-full">
                        <span class="mr-2 uppercase tracking-widest text-[10px] text-primary font-bold">{{ auth()->user()->role }}</span>
                        <span>{{ auth()->user()->name }}</span>
                    </div>

                    @if(auth()->user()->role === 'owner')
                        <a href="{{ route('owner.dashboard') }}" class="p-2 hover:bg-[#bff5c8] rounded-full transition-colors flex items-center" title="Dashboard">
                            <span class="material-symbols-outlined text-[#0052d0]">dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('bookings.index') }}" class="p-2 hover:bg-[#bff5c8] rounded-full transition-colors flex items-center" title="My Bookings">
                            <span class="material-symbols-outlined text-[#0052d0]">sports_soccer</span>
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 hover:bg-red-100 rounded-full transition-colors flex items-center" title="Logout">
                            <span class="material-symbols-outlined text-red-600">logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="font-headline font-bold text-sm bg-primary text-white px-5 py-2.5 rounded-full hover:bg-primary-dim transition-all active:scale-95 shadow-md shadow-primary/20">Sign In</a>
                    <a href="{{ route('register') }}" class="font-headline font-bold text-sm bg-surface-container text-[#0d361c] px-5 py-2.5 rounded-full hover:bg-surface-variant transition-all active:scale-95">Register</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow pt-24 min-h-screen">
        <!-- Toast Notification Container -->
        <div id="toast-container" class="fixed top-24 right-6 z-[100] flex flex-col gap-3 w-full max-w-md pointer-events-none" style="max-width: min(420px, calc(100vw - 3rem));">
            @if(session('success'))
                <div class="toast-notification pointer-events-auto flex items-start gap-4 bg-[#f0fdf4] border border-[#bbf7d0] rounded-2xl px-5 py-4 shadow-[0_20px_60px_rgba(0,128,0,0.15)] backdrop-blur-xl transform translate-x-[120%] opacity-0 transition-all duration-500 ease-out"
                     data-type="success">
                    <div class="bg-[#16a34a] p-2 rounded-xl flex items-center justify-center text-white shadow-md shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-headline font-bold text-[#14532d] text-sm">Berhasil!</h4>
                        <p class="text-[#166534] text-sm font-medium mt-0.5 leading-snug">{{ session('success') }}</p>
                    </div>
                    <button onclick="dismissToast(this.parentElement)" class="text-[#16a34a]/60 hover:text-[#16a34a] transition-colors shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                    <div class="absolute bottom-0 left-0 right-0 h-1 rounded-b-2xl overflow-hidden">
                        <div class="toast-progress h-full bg-[#16a34a]/30 rounded-b-2xl"></div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="toast-notification pointer-events-auto flex items-start gap-4 bg-[#fef2f2] border border-[#fecaca] rounded-2xl px-5 py-4 shadow-[0_20px_60px_rgba(220,38,38,0.15)] backdrop-blur-xl transform translate-x-[120%] opacity-0 transition-all duration-500 ease-out"
                     data-type="error">
                    <div class="bg-[#dc2626] p-2 rounded-xl flex items-center justify-center text-white shadow-md shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">error</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-headline font-bold text-[#7f1d1d] text-sm">Gagal!</h4>
                        <p class="text-[#991b1b] text-sm font-medium mt-0.5 leading-snug">{{ session('error') }}</p>
                    </div>
                    <button onclick="dismissToast(this.parentElement)" class="text-[#dc2626]/60 hover:text-[#dc2626] transition-colors shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                    <div class="absolute bottom-0 left-0 right-0 h-1 rounded-b-2xl overflow-hidden">
                        <div class="toast-progress h-full bg-[#dc2626]/30 rounded-b-2xl"></div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="toast-notification pointer-events-auto flex items-start gap-4 bg-[#fef2f2] border border-[#fecaca] rounded-2xl px-5 py-4 shadow-[0_20px_60px_rgba(220,38,38,0.15)] backdrop-blur-xl transform translate-x-[120%] opacity-0 transition-all duration-500 ease-out"
                     data-type="error" data-duration="8000">
                    <div class="bg-[#dc2626] p-2 rounded-xl flex items-center justify-center text-white shadow-md shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">warning</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-headline font-bold text-[#7f1d1d] text-sm">Validasi Gagal</h4>
                        <ul class="text-[#991b1b] text-sm font-medium mt-1 leading-snug list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button onclick="dismissToast(this.parentElement)" class="text-[#dc2626]/60 hover:text-[#dc2626] transition-colors shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                    <div class="absolute bottom-0 left-0 right-0 h-1 rounded-b-2xl overflow-hidden">
                        <div class="toast-progress h-full bg-[#dc2626]/30 rounded-b-2xl"></div>
                    </div>
                </div>
            @endif
        </div>

        <script>
        function dismissToast(el) {
            el.style.transform = 'translateX(120%)';
            el.style.opacity = '0';
            setTimeout(function() { el.remove(); }, 500);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(function(toast, index) {
                // Stagger entrance animation
                setTimeout(function() {
                    toast.style.transform = 'translateX(0)';
                    toast.style.opacity = '1';
                }, 100 + (index * 150));

                // Auto-dismiss with progress bar
                var duration = parseInt(toast.getAttribute('data-duration')) || 5000;
                var progress = toast.querySelector('.toast-progress');
                if (progress) {
                    progress.style.width = '100%';
                    progress.style.transition = 'width ' + duration + 'ms linear';
                    setTimeout(function() { progress.style.width = '0%'; }, 150 + (index * 150));
                }

                setTimeout(function() {
                    dismissToast(toast);
                }, duration + 100 + (index * 150));
            });
        });
        </script>

        @yield('content')
    </main>

    <!-- BottomNavBar (Mobile Only) -->
    <nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-4 pb-6 pt-3 bg-[#ffffff]/85 backdrop-blur-2xl shadow-[0_-10px_30px_rgba(13,54,28,0.08)] rounded-t-[3rem] z-50">
        @auth
            @if(auth()->user()->role === 'owner')
                <a class="flex flex-col items-center justify-center py-2 px-6 rounded-full transition-all duration-300 {{ request()->routeIs('owner.dashboard') ? 'bg-[#00fc40] text-[#005a10]' : 'text-[#0d361c] opacity-40 hover:opacity-100' }}" href="{{ route('owner.dashboard') }}">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('owner.dashboard') ? '1' : '0' }}">dashboard</span>
                    <span class="font-headline text-[9px] font-semibold uppercase tracking-widest mt-1">Dashboard</span>
                </a>
                <a class="flex flex-col items-center justify-center py-2 px-6 rounded-full text-[#0d361c] opacity-40 hover:opacity-100" href="{{ route('owner.dashboard') }}#recent-activity">
                    <span class="material-symbols-outlined">history</span>
                    <span class="font-headline text-[9px] font-semibold uppercase tracking-widest mt-1">Activity</span>
                </a>
            @else
                <a class="flex flex-col items-center justify-center py-2 px-6 rounded-full transition-all duration-300 {{ request()->routeIs('fields.index') ? 'bg-[#00fc40] text-[#005a10]' : 'text-[#0d361c] opacity-40 hover:opacity-100' }}" href="{{ route('fields.index') }}">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('fields.index') ? '1' : '0' }}">search</span>
                    <span class="font-headline text-[9px] font-semibold uppercase tracking-widest mt-1">Explore</span>
                </a>
                <a class="flex flex-col items-center justify-center py-2 px-6 rounded-full transition-all duration-300 {{ request()->routeIs('bookings.index') ? 'bg-[#00fc40] text-[#005a10]' : 'text-[#0d361c] opacity-40 hover:opacity-100' }}" href="{{ route('bookings.index') }}">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('bookings.index') ? '1' : '0' }}">sports_soccer</span>
                    <span class="font-headline text-[9px] font-semibold uppercase tracking-widest mt-1">Bookings</span>
                </a>
            @endif

            <form action="{{ route('logout') }}" method="POST" id="logout-form-mobile" class="hidden">
                @csrf
            </form>
            <a class="flex flex-col items-center justify-center py-2 px-6 rounded-full text-red-600 opacity-60 hover:opacity-100" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                <span class="material-symbols-outlined">logout</span>
                <span class="font-headline text-[9px] font-semibold uppercase tracking-widest mt-1">Logout</span>
            </a>
        @else
            <a class="flex flex-col items-center justify-center py-2 px-6 rounded-full transition-all duration-300 {{ request()->routeIs('fields.index') ? 'bg-[#00fc40] text-[#005a10]' : 'text-[#0d361c] opacity-40 hover:opacity-100' }}" href="{{ route('fields.index') }}">
                <span class="material-symbols-outlined">search</span>
                <span class="font-headline text-[9px] font-semibold uppercase tracking-widest mt-1">Explore</span>
            </a>
            <a class="flex flex-col items-center justify-center py-2 px-6 rounded-full text-[#0d361c] opacity-40 hover:opacity-100" href="{{ route('login') }}">
                <span class="material-symbols-outlined">login</span>
                <span class="font-headline text-[9px] font-semibold uppercase tracking-widest mt-1">Sign In</span>
            </a>
        @endauth
    </nav>
    @yield('scripts')
</body>
</html>
