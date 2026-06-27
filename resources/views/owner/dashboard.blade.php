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

    <!-- Analysis Line Chart -->
    <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-variant/20 mb-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-6">
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-4">
                    <h3 id="chart-month-label" class="font-headline font-extrabold text-2xl tracking-tight text-on-surface">Analisis Performa Bisnis</h3>
                    <div class="flex gap-2">
                        <button id="btn-chart-prev" class="w-9 h-9 rounded-full border-2 border-surface-variant hover:bg-surface-variant/20 transition-all flex items-center justify-center active:scale-95"><span class="material-symbols-outlined text-sm text-on-surface">chevron_left</span></button>
                        <button id="btn-chart-next" class="w-9 h-9 rounded-full border-2 border-surface-variant hover:bg-surface-variant/20 transition-all flex items-center justify-center active:scale-95"><span class="material-symbols-outlined text-sm text-on-surface">chevron_right</span></button>
                    </div>
                </div>
                <p class="font-body text-sm font-medium text-on-surface-variant mt-1">Pendapatan dan jumlah booking berdasarkan interval bulan berjalan.</p>
            </div>
            <!-- Legend Indicators -->
            <div class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#0052d0]"></span>
                    <span class="font-body text-xs font-bold text-on-surface-variant">Total Pendapatan ($)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#00fc40] shadow-[0_0_8px_rgba(0,252,64,0.6)]"></span>
                    <span class="font-body text-xs font-bold text-on-surface-variant">Jumlah Booking</span>
                </div>
            </div>
        </div>
        <div class="relative w-full h-[320px] transition-opacity duration-300" id="chart-container-div">
            <canvas id="businessAnalysisChart"></canvas>
        </div>
    </div>

    <!-- Middle Section: Calendar & Active Fields -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Calendar View -->
        <div class="lg:col-span-2 bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-surface-variant/20 overflow-hidden">
            <!-- Field Filter -->
            <div class="mb-6">
                <label for="calendar-field-filter" class="font-label font-bold text-on-surface-variant tracking-widest text-[10px] uppercase mb-2 block">Filter Lapangan</label>
                <select id="calendar-field-filter" class="w-full md:w-auto bg-surface-container-high border border-surface-variant/30 rounded-xl px-5 py-3 font-body font-bold text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 transition-all cursor-pointer appearance-none" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-repeat: no-repeat; background-position: right 14px center;">
                    <option value="">Semua Lapangan</option>
                    @foreach($myFields as $field)
                        <option value="{{ $field->id }}">{{ $field->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Calendar Header -->
            <div class="flex justify-between items-center mb-10 px-2">
                <div>
                    <h2 id="calendar-month-label" class="font-headline font-black text-3xl tracking-tight text-on-surface"></h2>
                    <p class="font-body text-sm font-bold text-on-surface-variant/70 uppercase tracking-widest mt-1">Management Schedule</p>
                </div>
                <div class="flex gap-3">
                    <button id="btn-prev-month" class="w-12 h-12 rounded-full border-2 border-surface-variant hover:bg-surface-variant/20 transition-all flex items-center justify-center group active:scale-95"><span class="material-symbols-outlined text-on-surface group-hover:scale-110 transition-transform">chevron_left</span></button>
                    <button id="btn-next-month" class="w-12 h-12 rounded-full border-2 border-surface-variant hover:bg-surface-variant/20 transition-all flex items-center justify-center group active:scale-95"><span class="material-symbols-outlined text-on-surface group-hover:scale-110 transition-transform">chevron_right</span></button>
                </div>
            </div>

            <!-- Day Headers -->
            <div class="grid grid-cols-7 gap-4 mb-6 px-2">
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Mon</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Tue</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Wed</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Thu</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Fri</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Sat</div>
                <div class="text-center font-headline font-black text-on-surface-variant/40 text-xs uppercase tracking-tighter">Sun</div>
            </div>

            <!-- Calendar Grid (rendered by JS) -->
            <div id="calendar-grid" class="grid grid-cols-7 gap-y-8 gap-x-4 min-h-[320px] transition-opacity duration-300">
                <!-- JS will render day cells here -->
            </div>

            <!-- Legend -->
            <div class="flex flex-wrap items-center gap-6 mt-8 pt-6 border-t border-surface-variant/20 px-2">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full border-2 border-surface-variant/50"></div>
                    <span class="font-body text-xs font-bold text-on-surface-variant/60">Tersedia</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-neon-green shadow-[0_2px_8px_rgba(0,255,65,0.4)]"></div>
                    <span class="font-body text-xs font-bold text-on-surface-variant/60">Hari Ini</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-primary"></div>
                    <span class="font-body text-xs font-bold text-on-surface-variant/60">1–2 Booking</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-tertiary"></div>
                    <span class="font-body text-xs font-bold text-on-surface-variant/60">3+ Booking</span>
                </div>
            </div>
        </div>

        <!-- Lapangan Saya list -->
        <div class="flex flex-col gap-6">
            <div class="bg-surface-container-high p-6 rounded-lg shadow-sm">
                <h3 class="font-headline font-extrabold text-xl text-on-surface mb-6">Lapangan Saya</h3>
                <div class="space-y-4 max-h-[420px] overflow-y-auto pr-1" style="scrollbar-width: thin; scrollbar-color: rgba(0,0,0,0.15) transparent;">
                    @forelse($myFields as $field)
                        <div class="bg-surface-container-lowest p-4 rounded-xl flex items-center justify-between gap-4 border border-outline-variant/10">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 bg-surface-variant">
                                    @if($field->first_image_url)
                                        <img alt="{{ $field->name }}" class="w-full h-full object-cover" src="{{ $field->first_image_url }}"/>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[#bff5c8] text-primary">
                                            <span class="material-symbols-outlined text-xl">sports_tennis</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-body font-bold text-sm text-on-surface truncate">{{ $field->name }}</p>
                                    <span class="text-[10px] font-label font-bold uppercase text-on-surface-variant/60">{{ $field->sport_type }} • {{ $field->is_indoor ? 'Indoor' : 'Outdoor' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-1.5 shrink-0">
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    // --- State ---
    const now = new Date();
    let currentYear = now.getFullYear();
    let currentMonth = now.getMonth() + 1; // 1-indexed
    let selectedFieldId = '';
    let isLoading = false;

    // --- Month names ---
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    // --- Chart.js ---
    const chartCtx = document.getElementById('businessAnalysisChart');
    const chartMonthLabel = document.getElementById('chart-month-label');
    const btnChartPrev = document.getElementById('btn-chart-prev');
    const btnChartNext = document.getElementById('btn-chart-next');
    const chartContainer = document.getElementById('chart-container-div');
    
    let businessChart = null;
    let chartYear = now.getFullYear();
    let chartMonth = now.getMonth() + 1; // 1-indexed
    let isChartLoading = false;

    if (chartCtx) {
        businessChart = new Chart(chartCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Total Revenue ($)',
                        data: [],
                        borderColor: '#0052d0',
                        backgroundColor: 'rgba(0, 82, 208, 0.05)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Jumlah Booking',
                        data: [],
                        borderColor: '#00fc40',
                        backgroundColor: 'rgba(0, 252, 64, 0.05)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 12,
                        cornerRadius: 8,
                        backgroundColor: '#0d361c',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 10
                            },
                            color: '#64748b'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 10
                            },
                            color: '#64748b',
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 10
                            },
                            color: '#64748b',
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });

        // Load chart data function
        async function loadChartData() {
            if (isChartLoading) return;
            isChartLoading = true;
            chartContainer.style.opacity = '0.4';

            const url = new URL('/owner/chart-data', window.location.origin);
            url.searchParams.set('year', chartYear);
            url.searchParams.set('month', chartMonth);

            try {
                const res = await fetch(url.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                if (!res.ok) throw new Error('Failed to fetch chart data');

                const data = await res.json();
                
                // Update chart title
                chartMonthLabel.textContent = 'Analisis Performa - ' + monthNames[chartMonth - 1] + ' ' + chartYear;

                // Update chart datasets
                businessChart.data.labels = data.labels || [];
                businessChart.data.datasets[0].data = data.revenue || [];
                businessChart.data.datasets[1].data = data.bookings || [];
                businessChart.update();
            } catch (err) {
                console.error('Chart load error:', err);
            } finally {
                isChartLoading = false;
                chartContainer.style.opacity = '1';
            }
        }

        // Add event listeners for chart navigation
        btnChartPrev.addEventListener('click', () => {
            chartMonth--;
            if (chartMonth < 1) {
                chartMonth = 12;
                chartYear--;
            }
            loadChartData();
        });

        btnChartNext.addEventListener('click', () => {
            chartMonth++;
            if (chartMonth > 12) {
                chartMonth = 1;
                chartYear++;
            }
            loadChartData();
        });

        // Initial chart load
        loadChartData();
    }
    // --- DOM refs ---
    const grid = document.getElementById('calendar-grid');
    const monthLabel = document.getElementById('calendar-month-label');
    const btnPrev = document.getElementById('btn-prev-month');
    const btnNext = document.getElementById('btn-next-month');
    const fieldFilter = document.getElementById('calendar-field-filter');

    // --- Helpers ---
    function daysInMonth(year, month) {
        return new Date(year, month, 0).getDate();
    }

    function firstDayOffset(year, month) {
        // Day of week for the 1st of the month, shifted so Monday = 0
        const day = new Date(year, month - 1, 1).getDay();
        return (day + 6) % 7;
    }

    function pad(n) {
        return n < 10 ? '0' + n : '' + n;
    }

    function isToday(year, month, day) {
        return year === now.getFullYear() && month === (now.getMonth() + 1) && day === now.getDate();
    }

    // --- Render ---
    function renderCalendar(bookingsMap) {
        const totalDays = daysInMonth(currentYear, currentMonth);
        const offset = firstDayOffset(currentYear, currentMonth);

        // Update header
        monthLabel.textContent = monthNames[currentMonth - 1] + ' ' + currentYear;

        let html = '';

        // Offset cells
        for (let o = 0; o < offset; o++) {
            html += `<div class="flex flex-col items-center opacity-10">
                <div class="w-14 h-14 rounded-full flex items-center justify-center font-headline font-bold text-lg">-</div>
            </div>`;
        }

        // Day cells
        for (let d = 1; d <= totalDays; d++) {
            const dateKey = currentYear + '-' + pad(currentMonth) + '-' + pad(d);
            const count = bookingsMap[dateKey] || 0;
            const today = isToday(currentYear, currentMonth, d);

            // Circle classes
            let circleClasses = 'w-14 h-14 rounded-full transition-all flex items-center justify-center font-headline font-bold text-lg ';
            if (today) {
                circleClasses += 'bg-neon-green text-on-secondary-fixed shadow-[0_8px_20px_rgba(0,255,65,0.4)] font-black';
            } else if (count > 0) {
                circleClasses += 'border-2 border-primary/30 hover:border-primary/60 text-on-surface bg-primary/5';
            } else {
                circleClasses += 'border-2 border-transparent hover:border-surface-variant/50 text-on-surface';
            }

            // Dots
            let dotsHtml = '';
            if (count >= 1 && count <= 2) {
                dotsHtml = '<div class="w-1.5 h-1.5 rounded-full bg-primary"></div>';
            } else if (count >= 3) {
                dotsHtml = '<div class="w-1.5 h-1.5 rounded-full bg-primary"></div><div class="w-1.5 h-1.5 rounded-full bg-tertiary"></div>';
            }

            // Tooltip
            let tooltip = '';
            if (count > 0) {
                tooltip = `title="${count} booking${count > 1 ? 's' : ''}"`;
            }

            html += `<div class="flex flex-col items-center group cursor-pointer ${today ? 'scale-110' : ''}" ${tooltip}>
                <div class="${circleClasses}">
                    ${pad(d)}
                </div>
                <div class="flex gap-1 mt-2">
                    ${dotsHtml}
                </div>
                ${count > 0 ? `<span class="text-[10px] font-label font-bold text-on-surface-variant/50 mt-0.5 opacity-0 group-hover:opacity-100 transition-opacity">${count}x</span>` : ''}
            </div>`;
        }

        grid.innerHTML = html;
    }

    // --- Fetch ---
    async function loadCalendar() {
        if (isLoading) return;
        isLoading = true;

        // Fade out
        grid.style.opacity = '0.4';

        const url = new URL('/owner/calendar-bookings', window.location.origin);
        url.searchParams.set('year', currentYear);
        url.searchParams.set('month', currentMonth);
        if (selectedFieldId) {
            url.searchParams.set('field_id', selectedFieldId);
        }

        try {
            const res = await fetch(url.toString(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!res.ok) throw new Error('Failed to fetch calendar data');

            const data = await res.json();
            renderCalendar(data.bookings || {});
        } catch (err) {
            console.error('Calendar load error:', err);
            // Render empty calendar on error
            renderCalendar({});
        } finally {
            isLoading = false;
            grid.style.opacity = '1';
        }
    }

    // --- Navigation ---
    function changeMonth(delta) {
        currentMonth += delta;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        } else if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        loadCalendar();
    }

    // --- Event Listeners ---
    btnPrev.addEventListener('click', function() { changeMonth(-1); });
    btnNext.addEventListener('click', function() { changeMonth(1); });

    fieldFilter.addEventListener('change', function(e) {
        selectedFieldId = e.target.value;
        loadCalendar();
    });

    // --- Init ---
    loadCalendar();
})();
</script>
@endsection
