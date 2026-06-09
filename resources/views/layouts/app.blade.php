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
                            <a class="font-headline font-bold text-lg tracking-tight py-1 {{ request()->routeIs('owner.fields.*') ? 'text-[#0052d0] border-b-4 border-[#0052d0]' : 'text-[#0d361c] opacity-60 hover:bg-[#bff5c8] px-3 rounded-lg transition-colors' }}" href="{{ route('owner.dashboard') }}">My Fields</a>
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
        <!-- Success/Error Banner Alerts -->
        <div class="max-w-screen-2xl mx-auto px-6 mt-4">
            @if(session('success'))
                <div class="mb-6 p-5 bg-secondary-container bg-opacity-20 rounded-lg flex items-center gap-4 border-l-8 border-secondary-container">
                    <div class="bg-secondary p-2.5 rounded-full flex items-center justify-center text-white shadow-md">
                        <span class="material-symbols-outlined font-bold">check_circle</span>
                    </div>
                    <div>
                        <h4 class="font-headline font-bold text-on-surface">Action Succeeded</h4>
                        <p class="text-on-surface-variant text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-5 bg-error-container bg-opacity-20 rounded-lg flex items-center gap-4 border-l-8 border-error-container">
                    <div class="bg-error p-2.5 rounded-full flex items-center justify-center text-[#ffefec] shadow-md">
                        <span class="material-symbols-outlined font-bold">warning</span>
                    </div>
                    <div>
                        <h4 class="font-headline font-bold text-error">Process Interrupted</h4>
                        <p class="text-error-dim text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-5 bg-error-container bg-opacity-20 rounded-lg flex flex-col gap-2 border-l-8 border-error-container">
                    <div class="flex items-center gap-4">
                        <div class="bg-error p-2.5 rounded-full flex items-center justify-center text-[#ffefec] shadow-md">
                            <span class="material-symbols-outlined font-bold">error</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-bold text-error">Validation Failed</h4>
                            <p class="text-error-dim text-sm font-medium">Please correct the fields below and submit again.</p>
                        </div>
                    </div>
                    <ul class="list-disc list-inside text-sm text-error-dim pl-14 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

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
