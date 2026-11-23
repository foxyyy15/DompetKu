@php
    use App\Enums\TransactionType;
    use App\Models\Transaction;
    use App\Support\Format;
@endphp

<x-layouts.app title="Ringkasan Dompet & Arus Kas" :headerMonth="Format::monthYear($selectedMonth)">
    {{-- Page Header & Month Switcher --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-[11px] font-bold tracking-[0.12em] text-emerald-700 uppercase">
                Dashboard Finansial <span class="mx-1 text-emerald-500">&bull;</span> <span class="font-semibold tracking-normal text-emerald-600 normal-case">Terupdate Real-Time</span>
            </p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-[28px]">
                Ringkasan Dompet &amp; Arus Kas
            </h1>
        </div>

        <div class="inline-flex items-center gap-1 rounded-2xl border border-slate-200/80 bg-white p-1.5 shadow-2xs">
            <a href="{{ route('home', ['month' => $prevMonth->format('Y-m'), 'type' => $filterType]) }}"
               class="flex h-8 w-8 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
               title="Bulan Sebelumnya">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>

            <a href="{{ route('home', ['month' => $selectedMonth->format('Y-m'), 'type' => $filterType]) }}"
               class="inline-flex items-center gap-1.5 rounded-xl bg-[#084734] px-3.5 py-1.5 text-xs font-bold text-white shadow-2xs">
                <svg class="h-3.5 w-3.5 opacity-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                <span>{{ Format::monthYear($selectedMonth) }}</span>
            </a>

            <a href="{{ route('home', ['month' => $nextMonth->format('Y-m'), 'type' => $filterType]) }}"
               class="rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                {{ Format::monthYear($nextMonth) }}
            </a>

            <a href="{{ route('home', ['month' => $nextMonth->format('Y-m'), 'type' => $filterType]) }}"
               class="flex h-8 w-8 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
               title="Bulan Berikutnya">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
    </div>

    {{-- 3 Summary KPI Cards --}}
    <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-[1.45fr_1fr_1fr]">
        {{-- Total Saldo Kas --}}
        @php $isDeficit = $totalBalance < 0; @endphp
        <div class="{{ $isDeficit ? 'balance-card-gradient-danger shadow-[0_14px_34px_-12px_rgba(153,27,27,0.55)]' : 'balance-card-gradient shadow-[0_14px_34px_-12px_rgba(8,71,52,0.5)]' }} relative overflow-hidden rounded-2xl p-6 text-white">
            <div class="pointer-events-none absolute -top-10 -right-10 h-40 w-40 rounded-full {{ $isDeficit ? 'bg-rose-400/15' : 'bg-emerald-400/10' }} blur-2xl"></div>
            <div class="flex items-center justify-between">
                <div class="inline-flex items-center gap-2 text-[11px] font-bold tracking-[0.12em] {{ $isDeficit ? 'text-rose-200/90' : 'text-emerald-200/90' }} uppercase">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <circle cx="12" cy="12" r="2" />
                        <path d="M6 12h.01M18 12h.01" />
                    </svg>
                    <span>Total Saldo Kas</span>
                </div>
                <span class="rounded-full bg-white/15 px-2.5 py-0.5 text-[11px] font-semibold {{ $isDeficit ? 'text-rose-100' : 'text-emerald-100' }}">
                    {{ $isDeficit ? 'Defisit' : 'Aktif' }}
                </span>
            </div>
            <p class="mt-5 text-3xl font-extrabold tracking-tight sm:text-4xl">
                {{ Format::rupiah($totalBalance) }}
            </p>
        </div>

        {{-- Pemasukan --}}
        <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-[0.12em] text-slate-400 uppercase">Pemasukan</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14" />
                        <path d="m19 12-7 7-7-7" />
                    </svg>
                </span>
            </div>
            <p class="mt-6 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-[28px]">
                {{ Format::rupiah($monthlyIncome) }}
            </p>
        </div>

        {{-- Pengeluaran --}}
        <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-[0.12em] text-slate-400 uppercase">Pengeluaran</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 19V5" />
                        <path d="m5 12 7-7 7 7" />
                    </svg>
                </span>
            </div>
            <p class="mt-6 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-[28px]">
                {{ Format::rupiah($monthlyExpense) }}
            </p>
        </div>
    </div>

    {{-- Daftar Transaksi --}}
    <div class="mt-7">
        <div class="mb-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" />
                        <path d="M8 7h8M8 11h8M8 15h5" />
                    </svg>
                    <h2 class="text-lg font-extrabold text-slate-900">Daftar Transaksi</h2>
                    <span class="rounded-full bg-sky-100/80 px-2.5 py-0.5 text-[11px] font-bold text-sky-800">
                        {{ $transactionCount }} Transaksi
                    </span>
                </div>

                {{-- Filter Dropdown --}}
                <div class="relative">
                    <button type="button" onclick="toggleFilterMenu()" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 cursor-pointer" title="Filter Transaksi">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M7 12h10M10 18h4"/></svg>
                    </button>
                    <div id="tx-filter-menu" class="absolute right-0 z-20 mt-2 hidden w-44 rounded-xl border border-slate-100 bg-white p-1.5 shadow-lg">
                        <a href="{{ route('home', ['month' => $selectedMonth->format('Y-m'), 'type' => 'all']) }}" class="block rounded-lg px-3 py-2 text-xs font-semibold {{ $filterType === 'all' ? 'bg-[#084734] text-white' : 'text-slate-700 hover:bg-slate-50' }}">Semua Transaksi</a>
                        <a href="{{ route('home', ['month' => $selectedMonth->format('Y-m'), 'type' => 'income']) }}" class="block rounded-lg px-3 py-2 text-xs font-semibold {{ $filterType === 'income' ? 'bg-[#084734] text-white' : 'text-slate-700 hover:bg-slate-50' }}">Hanya Pemasukan</a>
                        <a href="{{ route('home', ['month' => $selectedMonth->format('Y-m'), 'type' => 'expense']) }}" class="block rounded-lg px-3 py-2 text-xs font-semibold {{ $filterType === 'expense' ? 'bg-[#084734] text-white' : 'text-slate-700 hover:bg-slate-50' }}">Hanya Pengeluaran</a>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-2xs">
                @forelse ($groupedTransactions as $group)
                    {{-- Date Group Header --}}
                    <div class="flex items-center justify-between border-b border-slate-100 bg-[#f1f5f9]/80 px-5 py-2.5">
                        <div class="inline-flex items-center gap-2 text-xs font-bold text-slate-700">
                            <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            <span>{{ $group['date_label'] }}</span>
                        </div>
                        <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">{{ $group['day_label'] }}</span>
                    </div>

                    {{-- Transactions in Date Group --}}
                    <div class="divide-y divide-slate-100">
                        @foreach ($group['items'] as $tx)
                            @php
                                $meta = Transaction::metaFor($tx->category);
                                $isIncome = $tx->type === TransactionType::Income;
                            @endphp
                            <div class="group flex items-center justify-between gap-4 px-5 py-4 transition hover:bg-slate-50/70">
                                <div class="flex min-w-0 items-center gap-3.5">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $meta['icon_bg'] }} {{ $meta['icon_text'] }}">
                                        <x-category-icon :name="$meta['icon']" class="h-5 w-5" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-900">{{ $tx->title }}</p>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="rounded-md px-2 py-0.5 text-[10px] font-bold {{ $meta['badge_bg'] }} {{ $meta['badge_text'] }}">
                                                {{ $meta['label'] }}
                                            </span>
                                            <span class="truncate text-xs text-slate-400">{{ $tx->description }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex shrink-0 items-center gap-3">
                                    <div class="text-right">
                                        <p class="text-sm font-extrabold {{ $isIncome ? 'text-emerald-700' : 'text-rose-700' }}">
                                            {{ $isIncome ? '+ ' : '- ' }}{{ Format::rupiah($tx->amount) }}
                                        </p>
                                        <p class="mt-0.5 text-[11px] font-medium text-slate-400">
                                            {{ $tx->occurred_at->format('H:i') }} WIB
                                        </p>
                                    </div>

                                    <form method="POST" action="{{ route('transactions.destroy', $tx) }}" onsubmit="return confirm('Hapus transaksi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-1.5 text-slate-300 opacity-0 transition group-hover:opacity-100 hover:bg-rose-50 hover:text-rose-600 cursor-pointer" title="Hapus Transaksi">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div class="px-6 py-14 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/></svg>
                        </div>
                        <p class="mt-3 text-sm font-bold text-slate-700">Belum ada transaksi di bulan ini</p>
                        <p class="mt-1 text-xs text-slate-400">Klik tombol <strong>+ Tambah Transaksi</strong> di kanan atas untuk mulai mencatat.</p>
                    </div>
                @endforelse
            </div>
    </div>

    <script>
        function toggleFilterMenu() {
            const menu = document.getElementById('tx-filter-menu');
            if (menu) menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function (e) {
            const menu = document.getElementById('tx-filter-menu');
            if (menu && !menu.classList.contains('hidden') && !menu.parentElement.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</x-layouts.app>
