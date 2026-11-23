@php
    use App\Enums\TransactionType;
    use App\Support\Format;

    $isIncome = $type === TransactionType::Income;
    $strokeColor = $isIncome ? '#084734' : '#7f1d2e';
    $fillTopColor = $isIncome ? 'rgba(16, 185, 129, 0.20)' : 'rgba(159, 18, 57, 0.16)';

    // Build SVG chart coordinates (viewBox 640 x 280)
    $padLeft = 78;
    $padRight = 28;
    $padTop = 36;
    $padBottom = 32;
    $chartWidth = 640 - $padLeft - $padRight;
    $chartHeight = 280 - $padTop - $padBottom;
    $count = max(1, count($months));
    $stepX = $count > 1 ? $chartWidth / ($count - 1) : 0;

    $points = [];
    foreach ($months as $idx => $m) {
        $x = $padLeft + ($idx * $stepX);
        $ratio = $yMax > 0 ? min(1, $m['value'] / $yMax) : 0;
        $y = $padTop + $chartHeight - ($ratio * $chartHeight);
        $points[] = [
            'x' => round($x, 1),
            'y' => round($y, 1),
            'short' => $m['short'],
            'label' => $m['label'],
            'value' => $m['value'],
            'is_active' => $m['is_active'],
        ];
    }

    // Smooth cubic bezier path
    $linePath = '';
    foreach ($points as $i => $pt) {
        if ($i === 0) {
            $linePath .= "M {$pt['x']} {$pt['y']}";
        } else {
            $prev = $points[$i - 1];
            $midX = round(($prev['x'] + $pt['x']) / 2, 1);
            $linePath .= " C {$midX} {$prev['y']}, {$midX} {$pt['y']}, {$pt['x']} {$pt['y']}";
        }
    }

    $baseY = $padTop + $chartHeight;
    $firstX = $points[0]['x'] ?? $padLeft;
    $lastX = $points[count($points) - 1]['x'] ?? ($padLeft + $chartWidth);
    $areaPath = $linePath." L {$lastX} {$baseY} L {$firstX} {$baseY} Z";

    $avgRatio = $yMax > 0 ? min(1, $averageValue / $yMax) : 0;
    $avgY = round($padTop + $chartHeight - ($avgRatio * $chartHeight), 1);

    $yTicks = [];
    for ($i = 0; $i <= 5; $i++) {
        $val = (int) round(($yMax / 5) * $i);
        $yPos = round($baseY - (($chartHeight / 5) * $i), 1);
        $yTicks[] = ['val' => $val, 'y' => $yPos];
    }
@endphp

<x-layouts.app title="Laporan Tren {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}" :headerMonth="Format::monthYear($selectedMonth)">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-[11px] font-bold tracking-[0.12em] text-slate-400 uppercase">
                Laporan <span class="mx-1">/</span> <span class="text-emerald-700">Analisis Tren</span>
            </p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-[30px]">
                Laporan Tren {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
            </h1>
            <p class="mt-1.5 flex items-center gap-2 text-xs text-slate-500">
                <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                <span>Rentang Waktu: <strong class="text-slate-700">6 Bulan Terakhir</strong> ({{ $shortMonthsList }})</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200/80 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-2xs">
                <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                <span>{{ $rangeLabel }}</span>
            </div>

            <div class="inline-flex items-center rounded-xl border border-slate-200/80 bg-white p-1 shadow-2xs">
                <a href="{{ route('reports.index', ['type' => 'expense', 'month' => $selectedMonth->format('Y-m')]) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-[11px] font-extrabold tracking-wider uppercase transition {{ !$isIncome ? 'bg-[#084734] text-white' : 'text-slate-600 hover:text-slate-900' }}">
                    <span class="h-2 w-2 rounded-full {{ !$isIncome ? 'bg-rose-300' : 'bg-rose-700' }}"></span>
                    <span>Pengeluaran</span>
                </a>
                <a href="{{ route('reports.index', ['type' => 'income', 'month' => $selectedMonth->format('Y-m')]) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-[11px] font-extrabold tracking-wider uppercase transition {{ $isIncome ? 'bg-[#084734] text-white' : 'text-slate-600 hover:text-slate-900' }}">
                    <span class="h-2 w-2 rounded-full {{ $isIncome ? 'bg-emerald-300' : 'bg-emerald-500' }}"></span>
                    <span>Pemasukan</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Main 2-Column Content --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Left: Trend Line Chart Card --}}
        <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-2xs lg:col-span-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">
                        Tren {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }} Bulanan
                    </h2>
                    <p class="mt-0.5 text-xs text-slate-400">
                        Visualisasi kurva {{ $isIncome ? 'pemasukan' : 'pengeluaran' }} periode {{ Format::monthYear($startMonth) }} – {{ Format::monthYear($selectedMonth) }}
                    </p>
                </div>

                <div class="inline-flex items-center gap-4 rounded-xl bg-slate-50 px-3.5 py-2 text-[11px] font-bold text-slate-600">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $strokeColor }}"></span>
                        <span>{{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}</span>
                    </span>
                    <span class="h-3 w-px bg-slate-200"></span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>
                        <span>Rata-rata Normal</span>
                    </span>
                </div>
            </div>

            {{-- SVG Smooth Line Chart --}}
            <div class="relative mt-6">
                <svg viewBox="0 0 640 280" class="h-auto w-full overflow-visible" role="img" aria-label="Grafik Tren 6 Bulan">
                    <defs>
                        <linearGradient id="trendAreaGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="{{ $fillTopColor }}" />
                            <stop offset="100%" stop-color="rgba(255,255,255,0)" />
                        </linearGradient>
                    </defs>

                    {{-- Horizontal Grid Lines & Y-axis Labels --}}
                    @foreach ($yTicks as $tick)
                        <line x1="{{ $padLeft }}" y1="{{ $tick['y'] }}" x2="{{ 640 - $padRight }}" y2="{{ $tick['y'] }}"
                              stroke="{{ $tick['val'] === 0 ? '#cbd5e1' : '#f1f5f9' }}" stroke-width="1" />
                        <text x="{{ $padLeft - 12 }}" y="{{ $tick['y'] + 4 }}" text-anchor="end" fill="#64748b" font-size="10.5" font-weight="600">
                            {{ Format::rupiahShort($tick['val']) }}
                        </text>
                    @endforeach

                    {{-- X-axis Month Labels (Top aligned like design) --}}
                    @foreach ($points as $pt)
                        <text x="{{ $pt['x'] }}" y="16" text-anchor="middle"
                              fill="{{ $pt['is_active'] ? $strokeColor : '#64748b' }}"
                              font-size="11" font-weight="{{ $pt['is_active'] ? '800' : '600' }}">
                            {{ $pt['short'] }}
                        </text>
                    @endforeach

                    {{-- Average Dashed Reference Line --}}
                    <line x1="{{ $padLeft }}" y1="{{ $avgY }}" x2="{{ 640 - $padRight }}" y2="{{ $avgY }}"
                          stroke="#10b981" stroke-width="1.2" stroke-dasharray="4 4" opacity="0.65" />

                    {{-- Vertical Active Highlight Line --}}
                    @if (count($points) > 0)
                        @php $lastPt = $points[count($points) - 1]; @endphp
                        <line x1="{{ $lastPt['x'] }}" y1="{{ $padTop }}" x2="{{ $lastPt['x'] }}" y2="{{ $baseY }}"
                              stroke="{{ $strokeColor }}" stroke-width="1.2" stroke-dasharray="3 3" opacity="0.45" />
                    @endif

                    {{-- Area Fill --}}
                    <path d="{{ $areaPath }}" fill="url(#trendAreaGrad)" />

                    {{-- Smooth Curve --}}
                    <path d="{{ $linePath }}" fill="none" stroke="{{ $strokeColor }}" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" />

                    {{-- Data Points --}}
                    @foreach ($points as $pt)
                        <g class="group cursor-pointer">
                            <title>{{ $pt['label'] }}: {{ Format::rupiah($pt['value']) }}</title>
                            @if ($pt['is_active'])
                                <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="7" fill="{{ $strokeColor }}" opacity="0.18" />
                                <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="4.5" fill="{{ $strokeColor }}" stroke="#ffffff" stroke-width="2" />
                            @else
                                <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="4" fill="#ffffff" stroke="{{ $strokeColor }}" stroke-width="2.2" />
                            @endif
                        </g>
                    @endforeach
                </svg>
            </div>
        </div>

        {{-- Right: Ringkasan 6 Bulan Stack --}}
        <div class="space-y-4 lg:col-span-4">
            {{-- Header Card --}}
            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-2xs">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-extrabold text-slate-900">Ringkasan 6 Bulan</h2>
                    <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600">{{ $rangeLabel }}</span>
                </div>
                <p class="mt-1.5 text-xs leading-relaxed text-slate-400">
                    Akumulasi total arus kas masuk, realisasi beban, dan saldo neto.
                </p>
            </div>

            {{-- Total Pemasukan --}}
            <div class="flex items-center justify-between rounded-2xl border border-slate-200/70 bg-white p-5 shadow-2xs">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="m19 12-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="text-xs font-semibold text-slate-500">
                        <p>Total</p>
                        <p>Pemasukan</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-emerald-700">Rp</p>
                    <p class="text-xl font-extrabold tracking-tight text-emerald-700">
                        {{ number_format($totalIncome, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Total Pengeluaran --}}
            <div class="flex items-center justify-between rounded-2xl border border-slate-200/70 bg-white p-5 shadow-2xs">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 19V5" />
                            <path d="m5 12 7-7 7 7" />
                        </svg>
                    </div>
                    <div class="text-xs font-semibold text-slate-500">
                        <p>Total</p>
                        <p>Pengeluaran</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-rose-800">Rp</p>
                    <p class="text-xl font-extrabold tracking-tight text-rose-800">
                        {{ number_format($totalExpense, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Selisih Bersih --}}
            <div class="flex items-center justify-between rounded-2xl border border-sky-100 bg-gradient-to-br from-[#eef6fb] to-[#e4f0f8] p-5 shadow-2xs">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#084734] text-white shadow-xs">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="6" width="20" height="12" rx="2" />
                            <circle cx="12" cy="12" r="2" />
                        </svg>
                    </div>
                    <div class="text-xs font-bold text-slate-700">
                        <p>Selisih</p>
                        <p>Bersih</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-700">{{ $netBalance < 0 ? '-Rp' : 'Rp' }}</p>
                    <p class="text-xl font-extrabold tracking-tight text-slate-900">
                        {{ number_format(abs($netBalance), 0, ',', '.') }}
                    </p>
                    <span class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold {{ $netBalance >= 0 ? 'bg-[#084734] text-white' : 'bg-rose-600 text-white' }}">
                        {{ $netBalance >= 0 ? 'Status Sehat' : 'Defisit' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
