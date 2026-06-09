@extends('layouts.app')

@section('title', 'Recover Password')

@section('content')
<div class="relative min-h-[calc(100vh-6rem)] flex items-center justify-center p-6 bg-surface">
    <!-- Ornamentals -->
    <div class="absolute -top-12 -left-12 w-32 h-32 bg-secondary-container rounded-full blur-3xl opacity-30"></div>
    <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-primary-container rounded-full blur-3xl opacity-20"></div>

    <main class="w-full max-w-md relative z-10">
        <!-- Content Card -->
        <div class="relative bg-surface-container-lowest rounded-lg p-10 shadow-[0_20px_40px_rgba(13,54,28,0.06)] border border-white/40 overflow-hidden">
            <!-- Graphic Element -->
            <div class="mb-8 kinetic-tilt">
                <div class="w-16 h-16 bg-surface-container rounded-xl flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-secondary-container/20 to-transparent"></div>
                    <span class="material-symbols-outlined text-3xl text-secondary">lock_reset</span>
                </div>
            </div>

            <!-- Header Section -->
            <div class="space-y-3 mb-10">
                <h1 class="font-headline font-extrabold text-3xl text-on-surface tracking-tight leading-tight">
                    Recover your <span class="text-primary">raketinAJA</span>.
                </h1>
                <p class="text-on-surface-variant font-body leading-relaxed text-balance">
                    Enter the email address associated with your account and we'll send you a link to reset your password.
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-2">
                    <label class="font-headline text-[10px] font-bold uppercase tracking-[0.2em] text-on-surface-variant px-1" for="email">
                        Email Address
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-primary">
                            <span class="material-symbols-outlined text-xl">mail</span>
                        </div>
                        <input class="w-full h-14 bg-surface-variant/40 border-none rounded-xl pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all duration-300 font-body" id="email" name="email" placeholder="alex@example.com" required type="email"/>
                    </div>
                </div>

                <div class="space-y-6">
                    <button class="w-full h-14 bg-primary text-on-primary font-headline font-bold text-lg rounded-xl flex items-center justify-center gap-3 transition-all duration-300 hover:shadow-xl hover:shadow-primary/20 active:scale-95 group" type="submit">
                        <span>Send Reset Link</span>
                        <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </button>
                    
                    <div class="flex items-center justify-center">
                        <a class="inline-flex items-center gap-2 text-on-surface-variant font-headline text-sm font-semibold hover:text-primary transition-colors group" href="{{ route('login') }}">
                            <span class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform">keyboard_backspace</span>
                            Back to Login
                        </a>
                    </div>
                </div>
            </form>

            <!-- Reassurance Badge -->
            <div class="mt-12 pt-8 border-t border-surface-container flex items-center gap-4">
                <div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-sm text-on-secondary-container" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                </div>
                <p class="text-[11px] text-on-surface-variant font-medium leading-snug">
                    Your account security is our priority. raketinAJA uses end-to-end encrypted recovery protocols.
                </p>
            </div>
        </div>

        <!-- Editorial Quote -->
        <div class="mt-10 px-6">
            <p class="font-headline font-bold text-sm text-secondary uppercase tracking-[0.3em] text-center opacity-40">
                Performance never stops.
            </p>
        </div>
    </main>
</div>
@endsection
