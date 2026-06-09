@extends('layouts.app')

@section('title', 'Welcome Back')

@section('content')
<div class="relative min-h-[calc(100vh-6rem)] flex items-center justify-center p-4 overflow-hidden -mt-4">
    <!-- Background Layer with Asymmetric Tension -->
    <div class="fixed inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-tr from-[#0052d0]/40 to-[#00fc40]/20 mix-blend-overlay z-10"></div>
        <img class="w-full h-full object-cover filter blur-[2px] scale-110 -rotate-1 opacity-80" alt="dramatic wide angle shot of a modern basketball court inside a stadium with bright neon green lighting and deep shadows" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD0xztQrW3U4KCBPcY7SBaA02Hi7Gha4B6qEyw_SYehLRG2DQM2FTeRdF1bQigdCi3bK65HI0oaGUqBEXLXzcUqBolf4WG7d4ywzD3r6wQQnV3HgM_50CpIHv87fSTKgEUA2aY-vURocwPjA7rMNXWsvDQ6vryzfdw4T4hNoaS4f-8WxqAiNbNecSLYXkh9ZuJ10ENWDw27TTrvO0BmgL_c5CYVvRmQI_PlDhf_sZ556nCVo8Fbbmv664FItWSmj8Fz-uMKoKD31w"/>
    </div>

    <!-- Main Content Canvas -->
    <div class="relative z-20 w-full max-w-[480px]">
        <!-- Brand Identity Container -->
        <div class="mb-8 flex flex-col items-center kinetic-skew">
            <h1 class="font-headline font-black text-6xl italic tracking-tighter text-primary drop-shadow-[0_4px_12px_rgba(0,82,208,0.3)]">
                raketinAJA
            </h1>
            <div class="mt-2 inline-flex items-center gap-2 px-4 py-1 bg-secondary-container rounded-full">
                <span class="material-symbols-outlined text-on-secondary-container text-sm" style="font-variation-settings: 'FILL' 1;">bolt</span>
                <span class="font-label text-[10px] uppercase font-bold tracking-[0.2em] text-on-secondary-container">Performance Protocol</span>
            </div>
        </div>

        <!-- Login Card -->
        <div class="bg-surface/75 backdrop-blur-2xl rounded-lg p-8 md:p-12 shadow-[0_20px_40px_rgba(13,54,28,0.15)] border border-white/20">
            <header class="mb-8">
                <h2 class="font-headline font-bold text-3xl text-on-surface mb-2">Welcome Back</h2>
                <p class="text-on-surface-variant font-medium">Synchronize your session to continue.</p>
            </header>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant px-1" for="email">Identity (Email)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-outline">
                            <span class="material-symbols-outlined">alternate_email</span>
                        </div>
                        <input class="w-full bg-white py-4 pl-14 pr-6 rounded-xl border-none focus:ring-2 focus:ring-primary focus:bg-white transition-all duration-300 placeholder:text-outline-variant font-medium text-on-surface" id="email" name="email" value="{{ old('email') }}" placeholder="athlete@velocity.core" type="email" required autofocus/>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant" for="password">Access Key</label>
                        <a class="text-[11px] font-bold text-primary hover:text-primary-dim transition-colors uppercase tracking-wider" href="{{ route('password.request') }}">Forgot Password?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-outline">
                            <span class="material-symbols-outlined">lock</span>
                        </div>
                        <input class="w-full bg-white py-4 pl-14 pr-14 rounded-xl border-none focus:ring-2 focus:ring-primary focus:bg-white transition-all duration-300 placeholder:text-outline-variant font-medium text-on-surface" id="password" name="password" placeholder="••••••••" type="password" required/>
                        <button class="absolute inset-y-0 right-5 flex items-center text-outline-variant hover:text-primary transition-colors" type="button" onclick="togglePasswordVisibility()">
                            <span class="material-symbols-outlined" id="password-visibility-icon">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input class="peer appearance-none w-6 h-6 rounded-md bg-white checked:bg-secondary-container border-none transition-all cursor-pointer" type="checkbox" name="remember" id="remember"/>
                            <span class="material-symbols-outlined absolute text-on-secondary-container opacity-0 peer-checked:opacity-100 text-sm transition-opacity" style="font-variation-settings: 'wght' 700;">check</span>
                        </div>
                        <span class="text-sm font-semibold text-on-surface-variant group-hover:text-on-surface transition-colors">Keep me signed in</span>
                    </label>
                </div>

                <!-- Action Button -->
                <button class="w-full group relative overflow-hidden bg-primary py-5 rounded-xl flex items-center justify-center gap-3 active:scale-95 transition-all duration-200 shadow-lg shadow-primary/20" type="submit">
                    <!-- Momentum Shine Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-in-out"></div>
                    <span class="font-headline font-bold text-lg text-on-primary tracking-tight">Enter raketinAJA</span>
                    <span class="material-symbols-outlined text-on-primary group-hover:translate-x-1 transition-transform">arrow_forward_ios</span>
                </button>
            </form>

            <!-- Secondary Path -->
            <footer class="mt-8 pt-6 border-t border-outline-variant/10 text-center">
                <p class="text-on-surface-variant text-sm font-medium">
                    New recruit? 
                    <a class="text-primary font-bold hover:underline underline-offset-4 ml-1 transition-all" href="{{ route('register') }}">Request Access</a>
                </p>
            </footer>
        </div>

        <!-- System Status Bar -->
        <div class="mt-6 flex justify-center gap-8 opacity-40">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-secondary-container"></div>
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-on-background">System Active</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-secondary-container"></div>
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-on-background">Secure Encrypted</span>
            </div>
        </div>
    </div>
    
    <!-- Floating Ornamentals -->
    <div class="fixed top-12 right-12 hidden lg:block rotate-12 opacity-15 pointer-events-none select-none">
        <span class="font-headline font-black text-[14rem] text-primary leading-none tracking-tighter">01</span>
    </div>
    <div class="fixed bottom-12 left-12 hidden lg:block -rotate-6 opacity-15 pointer-events-none select-none">
        <span class="font-headline font-black text-[10rem] text-secondary leading-none tracking-tighter italic">MVP</span>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('password-visibility-icon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            icon.textContent = 'visibility';
        }
    }
</script>
@endsection
