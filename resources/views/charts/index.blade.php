@php
    use App\Enums\TransactionType;
    use App\Support\Format;

    $isIncome = $type === TransactionType::Income;
    $radius = 74;
    $circumference = 2 * M_PI * $radius;
    $offset = 0.0;
    $donutSegments = [];
    foreach ($categoryBreakdown as $item) {
        $dashLength = ($item['percentage'] / 100) * $circumference;
        $donutSegments[] = [
            'color' => $item['color'],
            'dash' => round($dashLength, 2),
            'gap' => round($circumference - $dashLength, 2),
            'offset' => round($offset, 2),
            'label' => $item['label'],
            'percentage' => $item['percentage'],
        ];
        $offset += $dashLength;
    }
@endphp

<x-layouts.app title="Statistik {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}" :headerMonth="Format::monthYear($selectedMonth)">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-[11px] font-bold tracking-[0.12em] text-slate-400 uppercase">
                Laporan Finansial <span class="mx-1">/</span> <span class="text-emerald-700">{{ $isIncome ? 'Distribusi Pemasukan' : 'Distribusi Biaya' }}</span>
            </p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-[30px]">
                Statistik {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
            </h1>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Expense / Income Toggle --}}
            <div class="inline-flex items-center rounded-xl border border-slate-200/80 bg-white p-1 shadow-2xs">
                <a href="{{ route('charts.index', ['type' => 'expense', 'month' => $selectedMonth->format('Y-m')]) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-[11px] font-extrabold tracking-wider uppercase transition {{ !$isIncome ? 'bg-slate-100 text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}">
                    <span class="h-2 w-2 rounded-full bg-rose-700"></span>
                    <span>Pengeluaran</span>
                </a>
                <a href="{{ route('charts.index', ['type' => 'income', 'month' => $selectedMonth->format('Y-m')]) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-[11px] font-extrabold tracking-wider uppercase transition {{ $isIncome ? 'bg-slate-100 text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span>Pemasukan</span>
                </a>
            </div>

            {{-- Month Stepper --}}
            <div class="inline-flex items-center gap-1 rounded-xl border border-slate-200/80 bg-white px-2 py-1.5 shadow-2xs">
                <a href="{{ route('charts.index', ['type' => $type->value, 'month' => $prevMonth->format('Y-m')]) }}"
                   class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
                   title="Bulan Sebelumnya">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <span class="inline-flex items-center gap-1.5 px-2 text-xs font-bold text-slate-800">
                    <svg class="h-3.5 w-3.5 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    <span>{{ Format::monthYear($selectedMonth) }}</span>
                </span>
                <a href="{{ route('charts.index', ['type' => $type->value, 'month' => $nextMonth->format('Y-m')]) }}"
                   class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
                   title="Bulan Berikutnya">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Left Column (Composition + Category Breakdown) --}}
        <div class="space-y-6 lg:col-span-8">
            {{-- Komposisi Card --}}
            <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-2xs">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.12em] text-slate-400 uppercase">Visualisasi Portofolio</p>
                        <h2 class="mt-0.5 text-lg font-extrabold text-slate-900">
                            Komposisi {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
                        </h2>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-700">
                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                        <span>Total {{ $categoryBreakdown->count() }} Kategori Aktif</span>
                    </span>
                </div>

                <div class="mt-6 grid grid-cols-1 items-center gap-6 md:grid-cols-12">
                    {{-- Donut Chart --}}
                    <div class="flex flex-col items-center md:col-span-6">
                        <div class="relative h-56 w-56">
                            <svg viewBox="0 0 200 200" class="h-full w-full -rotate-90">
                                <circle cx="100" cy="100" r="{{ $radius }}" fill="none" stroke="#f1f5f9" stroke-width="26" />
                                @foreach ($donutSegments as $seg)
                                    <circle cx="100" cy="100" r="{{ $radius }}" fill="none"
                                            stroke="{{ $seg['color'] }}" stroke-width="26"
                                            stroke-dasharray="{{ $seg['dash'] }} {{ $seg['gap'] }}"
                                            stroke-dashoffset="-{{ $seg['offset'] }}">
                                        <title>{{ $seg['label'] }} ({{ Format::percent($seg['percentage']) }})</title>
                                    </circle>
                                @endforeach
                            </svg>

                            <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-center">
                                <span class="text-[9px] font-extrabold tracking-[0.12em] text-slate-400 uppercase">
                                    Total {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                                <span class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">
                                    {{ Format::rupiah($totalAmount) }}
                                </span>
                                <span class="mt-0.5 text-[11px] font-bold text-emerald-700">
                                    {{ Format::shortMonthYear($selectedMonth) }}
                                </span>
                            </div>
                        </div>

                        {{-- Donut Legend --}}
                        <div class="mt-4 flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-xs font-semibold text-slate-700">
                            @forelse ($categoryBreakdown as $item)
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $item['color'] }}"></span>
                                    <span>{{ $item['label'] }} ({{ Format::percent($item['percentage']) }})</span>
                                </span>
                            @empty
                                <span class="text-slate-400">Belum ada data pada bulan ini</span>
                            @endforelse
                        </div>
                    </div>

                    {{-- Right Insight Boxes --}}
                    <div class="space-y-4 md:col-span-6">
                        <div class="rounded-2xl bg-[#f1f5fa] p-5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-extrabold tracking-[0.1em] text-slate-500 uppercase">Rata-Rata Harian</span>
                                <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">Terkendali</span>
                            </div>
                            <p class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">
                                {{ Format::rupiah($dailyAverage) }}
                                <span class="text-xs font-semibold text-slate-500">/ hari</span>
                            </p>
                            <p class="mt-2 text-xs leading-relaxed text-slate-500">
                                Dihitung berdasarkan pembagian proporsional {{ $daysInMonth }} hari kalender {{ Format::monthYear($selectedMonth) }}.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-[#f1f5fa] p-5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-extrabold tracking-[0.1em] text-slate-500 uppercase">Kategori Dominan</span>
                                <span class="text-[11px] font-bold text-slate-500">Prioritas 1</span>
                            </div>
                            @if ($dominantCategory)
                                <div class="mt-2 flex items-baseline justify-between gap-2">
                                    <p class="text-base font-extrabold text-slate-900">{{ $dominantCategory['full_label'] }}</p>
                                    <span class="text-sm font-extrabold" style="color: {{ $dominantCategory['color'] }}">
                                        {{ Format::percent($dominantCategory['percentage']) }}
                                    </span>
                                </div>
                                <p class="mt-1.5 text-xs leading-relaxed text-slate-500">
                                    {{ $dominantCategory['dominant_desc'] }}
                                </p>
                            @else
                                <p class="mt-2 text-sm font-bold text-slate-600">Belum ada kategori</p>
                                <p class="mt-1 text-xs text-slate-400">Tambahkan transaksi untuk melihat kategori dominan bulan ini.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rincian per Kategori Card --}}
            <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-2xs">
                <div class="flex flex-wrap items-end justify-between gap-2">
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.12em] text-slate-400 uppercase">Breakdown Transaksi</p>
                        <h2 class="mt-0.5 text-lg font-extrabold text-slate-900">Rincian per Kategori</h2>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">Urut berdasarkan nominal terbesar</span>
                </div>

                <div class="mt-5 space-y-3.5">
                    @forelse ($categoryBreakdown as $item)
                        <div class="rounded-2xl bg-[#f3f6fb] p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-2xs" style="color: {{ $item['color'] }}">
                                        <x-category-icon :name="$item['icon']" class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-slate-900">{{ $item['label'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $item['subtitle'] }}</p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="text-sm font-extrabold text-slate-900">{{ Format::rupiah($item['amount']) }}</p>
                                    <p class="text-[11px] font-bold" style="color: {{ $item['color'] }}">
                                        {{ Format::percent($item['percentage']) }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3.5 h-2 w-full overflow-hidden rounded-full bg-slate-200/80">
                                <div class="h-full rounded-full transition-all duration-500"
                                     style="width: {{ max(3, min(100, $item['percentage'])) }}%; background-color: {{ $item['color'] }};"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl bg-slate-50 px-4 py-10 text-center text-xs font-medium text-slate-400">
                            Tidak ada transaksi {{ $isIncome ? 'pemasukan' : 'pengeluaran' }} pada {{ Format::monthYear($selectedMonth) }}.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column: Perbandingan 3 Bulan --}}
        <div class="lg:col-span-4">
            <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-2xs">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="text-base font-extrabold text-slate-900">Perbandingan 3 Bulan</h2>
                    @php
                        $deltaSign = $deltaPercent > 0 ? '+' : '';
                        $isFavorable = $isIncome ? $deltaPercent >= 0 : $deltaPercent <= 0;
                    @endphp
                    <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $isFavorable ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                        {{ $deltaSign }}{{ $deltaPercent }}% vs {{ $prevShortLabel }}
                    </span>
                </div>

                <div class="mt-5 space-y-5">
                    @foreach ($comparison as $row)
                        <div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="{{ $row['is_active'] ? 'font-extrabold text-emerald-800' : 'font-semibold text-slate-600' }}">
                                    {{ $row['label'] }}{{ $row['is_active'] ? ' (Aktif)' : '' }}
                                </span>
                                <span class="font-extrabold text-slate-900">{{ Format::rupiah($row['amount']) }}</span>
                            </div>
                            <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full transition-all duration-500 {{ $row['is_active'] ? 'bg-[#084734]' : 'bg-slate-500' }}"
                                     style="width: {{ max($row['amount'] > 0 ? 6 : 0, $row['width_percent']) }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
