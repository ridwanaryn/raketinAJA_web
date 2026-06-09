@extends('layouts.app')

@section('title', 'Request Access')

@section('content')
<div class="flex-grow flex flex-col md:flex-row min-h-[calc(100vh-6rem)] -mt-4 bg-surface-container-low">
    <!-- Left panel (desktop only) -->
    <section class="hidden md:flex md:w-5/12 lg:w-1/2 relative bg-secondary overflow-hidden items-center justify-center p-12">
        <div class="absolute inset-0 opacity-20">
            <img alt="Athletic action" class="w-full h-full object-cover grayscale scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA-22sdkH160dPZjfEdNdxvXaOizYuQfNC6-iFLri_Hh99rRUXoYMLxbYYCAR22h7u39lGQZ7xMg5cuHCOCpLqcyXNizdA4f24Zbul62tLHMNdlu7_nb2ib0IMxvD5I78F81gbA0oil1NVprrlXD0_3f2o3Fnp9v43styCMOJLblVMFLj_u21TfmfOFWnWSZvqPPa8ebGMZoCu21hCIMfOAoGvGULDTKSdnZXPpHQPIAL0jgfCoeeX_NeGh6auRRMqZo5VZIIJnlg"/>
        </div>
        <div class="absolute inset-0 bg-gradient-to-br from-primary/40 to-secondary/80 mix-blend-multiply"></div>
        <div class="relative z-10 space-y-8 kinetic-tilt">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl bg-secondary-container flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined text-on-secondary-container text-4xl">bolt</span>
                </div>
                <h1 class="font-headline font-black text-6xl text-surface-container-lowest italic tracking-tighter">Velocity Core</h1>
            </div>
            <p class="font-headline text-3xl font-bold text-surface tracking-tight leading-tight max-w-md">
                ENTER THE ARENA.<br/>UPGRADE YOUR GAME.
            </p>
            <div class="flex gap-2">
                <div class="h-1 w-12 bg-secondary-container rounded-full"></div>
                <div class="h-1 w-4 bg-surface-container-low rounded-full"></div>
                <div class="h-1 w-4 bg-surface-container-low rounded-full"></div>
            </div>
        </div>
        <div class="absolute bottom-12 left-12 right-12">
            <div class="glass-panel p-6 rounded-lg border-l-4 border-secondary-container">
                <p class="text-on-surface font-medium italic">"The fastest way to book professional courts and track your performance peak."</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="text-xs font-headline font-bold uppercase tracking-widest text-secondary">Arena Tech Insight</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Right panel (form) -->
    <section class="flex-grow flex items-center justify-center p-6 md:p-12 lg:p-20 bg-surface-container-low">
        <div class="w-full max-w-md space-y-8">
            <header class="space-y-2">
                <div class="md:hidden flex items-center gap-2 mb-6">
                    <span class="text-primary font-headline font-black text-2xl italic">Velocity Core</span>
                </div>
                <h2 class="font-headline font-extrabold text-4xl text-on-background tracking-tight">Create Account</h2>
                <p class="text-on-surface-variant font-medium">Join the next generation of athletic management.</p>
            </header>

            <form class="space-y-5" action="{{ route('register') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <!-- Role Picker -->
                    <div class="flex flex-col space-y-1">
                        <label class="text-[10px] font-headline font-bold uppercase tracking-widest text-secondary px-4">Identify As</label>
                        <div class="flex p-1 bg-surface-variant rounded-xl">
                            <label class="flex-1 cursor-pointer">
                                <input checked class="hidden peer" name="role" type="radio" value="player"/>
                                <span class="block text-center py-2 rounded-lg text-sm font-bold transition-all peer-checked:bg-primary peer-checked:text-white text-on-surface-variant">Player</span>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input class="hidden peer" name="role" type="radio" value="owner"/>
                                <span class="block text-center py-2 rounded-lg text-sm font-bold transition-all peer-checked:bg-primary peer-checked:text-white text-on-surface-variant">Owner</span>
                            </label>
                        </div>
                    </div>
                    <!-- Full Name -->
                    <div class="flex flex-col space-y-1">
                        <label class="text-[10px] font-headline font-bold uppercase tracking-widest text-secondary px-4" for="name">Full Name</label>
                        <input class="w-full px-5 py-3 rounded-xl bg-white border-none focus:ring-2 focus:ring-primary focus:bg-white transition-all placeholder:text-on-surface-variant/40 text-on-surface" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" type="text" required/>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex flex-col space-y-1">
                    <label class="text-[10px] font-headline font-bold uppercase tracking-widest text-secondary px-4" for="email">Email Address</label>
                    <input class="w-full px-5 py-3 rounded-xl bg-white border-none focus:ring-2 focus:ring-primary focus:bg-white transition-all placeholder:text-on-surface-variant/40 text-on-surface" id="email" name="email" value="{{ old('email') }}" placeholder="john@gmail.com" type="email" required/>
                </div>

                <!-- Phone -->
                <div class="flex flex-col space-y-1">
                    <label class="text-[10px] font-headline font-bold uppercase tracking-widest text-secondary px-4" for="phone">Phone Number</label>
                    <input class="w-full px-5 py-3 rounded-xl bg-white border-none focus:ring-2 focus:ring-primary focus:bg-white transition-all placeholder:text-on-surface-variant/40 text-on-surface" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+62 123456" type="tel" required/>
                </div>

                <!-- Password and Confirm -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col space-y-1">
                        <label class="text-[10px] font-headline font-bold uppercase tracking-widest text-secondary px-4" for="password">Password</label>
                        <input class="w-full px-5 py-3 rounded-xl bg-white border-none focus:ring-2 focus:ring-primary focus:bg-white transition-all placeholder:text-on-surface-variant/40 text-on-surface" id="password" name="password" placeholder="••••••••" type="password" required/>
                    </div>
                    <div class="flex flex-col space-y-1">
                        <label class="text-[10px] font-headline font-bold uppercase tracking-widest text-secondary px-4" for="password_confirmation">Confirm</label>
                        <input class="w-full px-5 py-3 rounded-xl bg-white border-none focus:ring-2 focus:ring-primary focus:bg-white transition-all placeholder:text-on-surface-variant/40 text-on-surface" id="password_confirmation" name="password_confirmation" placeholder="••••••••" type="password" required/>
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-start gap-3 px-2 py-2">
                    <div class="flex items-center h-5">
                        <input class="h-5 w-5 rounded-md border-none bg-surface-variant text-primary focus:ring-primary" id="terms" name="terms" type="checkbox" required/>
                    </div>
                    <label class="text-sm text-on-surface-variant leading-tight" for="terms">
                        I agree to the <a class="text-primary font-bold hover:underline" href="#">Terms and Conditions</a> and the <a class="text-primary font-bold hover:underline" href="#">Privacy Policy</a>.
                    </label>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button class="group relative w-full bg-gradient-to-br from-primary to-primary-container text-white font-headline font-extrabold text-lg py-4 rounded-xl shadow-[0_10px_30px_rgba(0,82,208,0.3)] hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3" type="submit">
                        Complete Registration
                        <span class="material-symbols-outlined text-2xl group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </div>

                <p class="text-center text-on-surface-variant font-medium pt-4">
                    Already have an account? 
                    <a class="text-primary font-bold hover:underline decoration-2 underline-offset-4" href="{{ route('login') }}">Sign In</a>
                </p>
            </form>

            <footer class="pt-8 border-t border-surface-container flex flex-wrap justify-center gap-6 grayscale opacity-50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">verified_user</span>
                    <span class="text-[10px] font-headline font-bold uppercase tracking-widest">Secure SSL</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">cloud_done</span>
                    <span class="text-[10px] font-headline font-bold uppercase tracking-widest">Cloud Sync</span>
                </div>
            </footer>
        </div>
    </section>
</div>
@endsection
