@extends('layouts.app')

@section('title', 'Arena Command Center')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 py-8">
    <!-- Dashboard Header & Action -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <h1 class="font-headline font-black text-4xl md:text-5xl tracking-tighter text-on-background mb-2">Arena Command Center</h1>
            <p class="font-body text-on-surface-variant font-medium">Monitoring peak performance across {{ $myFields->count() }} managed locations.</p>
        </div>
        <a href="{{ route('owner.fields.create') }}" class="bg-primary text-on-primary px-8 py-4 rounded-xl font-headline font-bold flex items-center gap-3 shadow-lg hover:scale-95 transition-transform">
            <span class="material-symbols-outlined">add_circle</span>
            Add New Field
        </a>
    </div>

    <!-- Bento Grid Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <!-- Revenue Stat -->
        <div class="md:col-span-2 bg-surface-container-lowest p-8 rounded-lg shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary-container/20 rounded-bl-full -mr-10 -mt-10"></div>
            <h3 class="font-label font-bold text-primary tracking-widest text-xs uppercase mb-4">Total Revenue (MTD)</h3>
            <div class="flex items-baseline gap-2 mb-6">
                <span class="font-headline font-black text-6xl tracking-tighter">${{ number_format($revenueMTD, 0) }}</span>
                <span class="font-body font-bold text-secondary text-sm flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">trending_up</span> +12.4%
                </span>
            </div>
            <div class="h-16 flex items-end gap-1">
                <div class="flex-1 bg-primary/10 rounded-t-full h-[40%]"></div>
                <div class="flex-1 bg-primary/10 rounded-t-full h-[60%]"></div>
                <div class="flex-1 bg-primary/10 rounded-t-full h-[55%]"></div>
                <div class="flex-1 bg-primary/20 rounded-t-full h-[75%]"></div>
                <div class="flex-1 bg-primary/20 rounded-t-full h-[90%]"></div>
                <div class="flex-1 bg-primary rounded-t-full h-[100%]"></div>
                <div class="flex-1 bg-primary/30 rounded-t-full h-[70%]"></div>
            </div>
        </div>
        
        <!-- Active Bookings Stat -->
        <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-label font-bold text-on-surface-variant tracking-widest text-xs uppercase mb-1">Active Bookings</h3>
                <span class="font-headline font-black text-5xl tracking-tighter text-on-surface">{{ $activeBookingsCount }}</span>
            </div>
            <div class="bg-secondary-container/30 rounded-xl p-4 mt-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-body text-xs font-bold text-on-secondary-container">Capacity</span>
                    <span class="font-body text-xs font-bold text-on-secondary-container">{{ $capacityPercent }}%</span>
                </div>
                <div class="w-full bg-surface-variant h-2 rounded-full overflow-hidden">
                    <div class="bg-secondary h-full" style="width: {{ $capacityPercent }}%"></div>
                </div>
            </div>
        </div>

        <!-- Performance Stat -->
        <div class="bg-secondary-fixed p-8 rounded-lg shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-label font-bold text-on-secondary-fixed tracking-widest text-xs uppercase mb-1">Peak Utilization</h3>
                <span class="font-headline font-black text-5xl tracking-tighter text-on-secondary-fixed">{{ $peakTime }}</span>
            </div>
            <p class="font-body text-sm font-bold text-on-secondary-fixed-variant mt-4">
                Prime hours are currently 92% booked for the next 14 days.
            </p>
        </div>
    </div>

    <!-- Middle Section: Calendar & Active Fields -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Calendar View -->
        <div class="lg:col-span-2 bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-variant/20 overflow-hidden">
            <div class="flex justify-between items-center mb-10 px-2">
                <div>
                    <h2 class="font-headline font-black text-3xl tracking-tight text-on-surface">{{ \Carbon\Carbon::now()->format('F Y') }}</h2>
                    <p class="font-body text-sm font-bold text-on-surface-variant/70 uppercase tracking-widest mt-1">Management Schedule</p>
                </div>
                <div class="flex gap-3">
                    <button class="w-12 h-12 rounded-full border-2 border-surface-variant hover:bg-surface-variant/20 transition-all flex items-center justify-center group active:scale-95"><span class="material-symbols-outlined text-on-surface group-hover:scale-110 transition-transform">chevron_left</span></button>
                    <button class="w-12 h-12 rounded-full border-2 border-surface-variant hover:bg-surface-variant/20 transition-all flex items-center justify-center group active:scale-95"><span class="material-symbols-outlined text-on-surface group-hover:scale-110 transition-transform">chevron_right</span></button>
                </div>
            </div>
            
            <div class="grid grid-cols-7 gap-4 mb-6 px-2">
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Mon</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Tue</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Wed</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Thu</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Fri</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Sat</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Sun</div>
            </div>

            <!-- Calendar Days Mockup -->
            <div class="grid grid-cols-7 gap-y-8 gap-x-4">
                @php
                    $startDay = \Carbon\Carbon::now()->startOfMonth();
                    $daysInMonth = $startDay->daysInMonth;
                    $dayOfWeekOffset = ($startDay->dayOfWeek + 6) % 7; // offset to align Monday
                    $todayNum = \Carbon\Carbon::now()->day;
                @endphp
                
                <!-- Offsets -->
                @for($o = 0; $o < $dayOfWeekOffset; $o++)
                    <div class="flex flex-col items-center opacity-10">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center font-headline font-bold text-lg">-</div>
                    </div>
                @endfor
                
                <!-- Days -->
                @for($d = 1; $d <= $daysInMonth; $d++)
                    @php
                        $isToday = ($d === $todayNum);
                    @endphp
                    <div class="flex flex-col items-center group cursor-pointer {{ $isToday ? 'scale-110' : '' }}">
                        <div class="w-14 h-14 rounded-full transition-all flex items-center justify-center font-headline font-bold text-lg
                            {{ $isToday ? 'bg-neon-green text-on-secondary-fixed shadow-[0_8px_20px_rgba(0,255,65,0.4)] font-black' : 'border-2 border-transparent hover:border-surface-variant/50 text-on-surface' }}">
                            {{ sprintf('%02d', $d) }}
                        </div>
                        <div class="flex gap-1 mt-2">
                            @if($d % 3 === 0)
                                <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                            @endif
                            @if($d % 5 === 0)
                                <div class="w-1.5 h-1.5 rounded-full bg-tertiary"></div>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Field Health list -->
        <div class="flex flex-col gap-6">
            <div class="bg-surface-container-high p-6 rounded-lg shadow-sm">
                <h3 class="font-headline font-extrabold text-xl text-on-surface mb-6">Field Health</h3>
                <div class="space-y-4">
                    @forelse($myFields as $field)
                        <div class="bg-surface-container-lowest p-4 rounded-xl flex items-center justify-between gap-4 border border-outline-variant/10">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 bg-surface-variant">
                                    @if($field->image_url)
                                        <img alt="{{ $field->name }}" class="w-full h-full object-cover" src="{{ $field->image_url }}"/>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[#bff5c8] text-primary">
                                            <span class="material-symbols-outlined text-xl">sports_tennis</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-body font-bold text-sm text-on-surface truncate">{{ $field->name }}</p>
                                    @if($field->status === 'active')
                                        <span class="text-[10px] font-label font-bold uppercase text-secondary">Maintenance OK</span>
                                    @elseif($field->status === 'cleaning')
                                        <span class="text-[10px] font-label font-bold uppercase text-tertiary">Cleaning Pending</span>
                                    @else
                                        <span class="text-[10px] font-label font-bold uppercase text-red-600">Maintenance Needed</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions (Edit, Delete, and Status Icon) -->
                            <div class="flex items-center gap-3 shrink-0">
                                @if($field->status === 'active')
                                    <span class="material-symbols-outlined text-secondary" title="Active">check_circle</span>
                                @else
                                    <span class="material-symbols-outlined text-tertiary" title="Warning">warning</span>
                                @endif
                                
                                <div class="flex gap-1.5">
                                    <a href="{{ route('owner.fields.edit', $field->id) }}" class="p-1 text-[#0052d0] hover:bg-[#bff5c8] rounded-full transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('owner.fields.destroy', $field->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this field?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-red-600 hover:bg-red-100 rounded-full transition-colors" title="Delete">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <span class="material-symbols-outlined text-4xl text-outline mb-2">add_location_alt</span>
                            <p class="text-on-surface-variant font-medium">No fields registered yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="bg-surface-container-lowest rounded-lg shadow-sm overflow-hidden" id="recent-activity">
        <div class="px-8 py-6 border-b-2 border-surface flex justify-between items-center">
            <h2 class="font-headline font-extrabold text-2xl tracking-tight text-on-surface">Recent Activity</h2>
        </div>
        
        <div class="divide-y divide-surface">
            @forelse($recentBookings as $b)
                <div class="px-8 py-6 flex items-center justify-between hover:bg-surface-container-low transition-colors">
                    <div class="flex items-center gap-6">
                        <div class="w-12 h-12 rounded-xl bg-primary-container/20 flex items-center justify-center shrink-0 text-primary">
                            <span class="material-symbols-outlined">
                                @if($b->field->sport_type === 'padel')
                                    sports_tennis
                                @elseif($b->field->sport_type === 'tennis')
                                    sports_tennis
                                @else
                                    sports_handball
                                @endif
                            </span>
                        </div>
                        <div>
                            <p class="font-body font-extrabold text-on-surface">New Booking: {{ $b->user->name }}</p>
                            <p class="font-body text-xs text-on-surface-variant font-medium">{{ $b->field->name }} • {{ $b->booking_date->format('M d, Y') }} ({{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }})</p>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <span class="font-headline font-black text-xl text-on-surface">+${{ number_format($b->total_price, 2) }}</span>
                        <p class="font-label text-[10px] font-bold text-secondary-dim uppercase">{{ $b->status }}</p>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-on-surface-variant font-medium">
                    No recent booking activity has been recorded yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
