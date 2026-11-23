@props([
    'title' => 'DompetKu',
    'headerMonth' => null,
])

@php
    $activeRoute = request()->route()?->getName();
    $displayMonth = $headerMonth ?? \App\Support\Format::monthYear(now());
    $categories = \App\Models\Transaction::categories();
    $modalCategories = collect($categories)->map(fn ($meta, $key) => [
        'value' => $key,
        'label' => $meta['full_label'],
        'type' => $meta['type'],
    ])->values()->all();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — DompetKu</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <script>
        (function () {
            @if (session('reset_theme'))
                localStorage.setItem('dompetku_theme', 'light');
            @endif
            if (localStorage.getItem('dompetku_theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f8fa] text-slate-900 antialiased">
    <div class="flex min-h-screen">
        {{-- Mobile Sidebar Overlay --}}
        <div id="mobile-sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-slate-900/40 backdrop-blur-xs lg:hidden" onclick="toggleMobileSidebar(false)"></div>

        {{-- Left Sidebar --}}
        <aside id="app-sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col justify-between border-r border-slate-200/70 bg-white px-5 py-6 transition-transform duration-200 lg:sticky lg:top-0 lg:h-screen lg:shrink-0 lg:translate-x-0">
            <div>
                <div class="sidebar-header mb-8 flex items-center justify-between px-2">
                    <a href="{{ route('home') }}" class="sidebar-brand focus:outline-none">
                        <x-brand-logo size="md" />
                    </a>

                    {{-- Desktop Collapse Toggle Button --}}
                    <div class="group/toggle relative hidden lg:block">
                        <button type="button" id="desktop-sidebar-toggle" onclick="toggleDesktopSidebar()" aria-label="Tutup sidebar"
                                class="relative flex h-10 w-10 items-center justify-center rounded-full text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 cursor-pointer">
                            <img src="{{ asset('images/logo-dompetku.png') }}"
                                 alt="Logo DompetKu"
                                 class="sidebar-collapsed-logo brand-logo-img pointer-events-none absolute h-8 w-8 object-contain">
                            <svg class="sidebar-toggle-icon h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="4" />
                                <path d="M9 3v18" />
                                <path d="m15 9-3 3 3 3" />
                            </svg>
                        </button>
                        <span id="sidebar-toggle-tooltip" class="pointer-events-none absolute top-1/2 left-full z-50 ml-2.5 -translate-y-1/2 whitespace-nowrap rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white opacity-0 shadow-lg transition-opacity duration-150 group-hover/toggle:opacity-100">
                            Tutup sidebar
                        </span>
                    </div>

                    <button type="button" onclick="toggleMobileSidebar(false)" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 lg:hidden" aria-label="Tutup menu">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <nav class="space-y-1.5" aria-label="Navigasi Utama">
                    <a href="{{ route('home') }}" title="Home"
                       class="sidebar-nav-link flex items-center gap-3.5 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ $activeRoute === 'home' ? 'bg-[#084734] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7" rx="1.5" />
                            <rect x="14" y="3" width="7" height="7" rx="1.5" />
                            <rect x="3" y="14" width="7" height="7" rx="1.5" />
                            <rect x="14" y="14" width="7" height="7" rx="1.5" />
                        </svg>
                        <span class="sidebar-label">Home</span>
                    </a>

                    <a href="{{ route('reports.index') }}" title="Reports"
                       class="sidebar-nav-link flex items-center gap-3.5 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ $activeRoute === 'reports.index' ? 'bg-[#084734] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 17l6-6 4 4 8-8" />
                            <path d="M5 21v-3" />
                            <path d="M10 21v-6" />
                            <path d="M15 21v-4" />
                            <path d="M20 21v-9" />
                        </svg>
                        <span class="sidebar-label">Reports</span>
                    </a>

                    <a href="{{ route('charts.index') }}" title="Charts"
                       class="sidebar-nav-link flex items-center gap-3.5 rounded-xl px-4 py-3 text-sm font-semibold transition-all {{ $activeRoute === 'charts.index' ? 'bg-[#084734] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 3v18" />
                            <path d="M12 12h9" />
                        </svg>
                        <span class="sidebar-label">Charts</span>
                    </a>
                </nav>
            </div>

            {{-- Bottom Controls: Dark Mode Switch + User Profile --}}
            <div class="space-y-2.5 pt-4">
                {{-- Sliding Dark Mode Toggle (Matahari -> Bulan) --}}
                <div class="sidebar-theme-row flex items-center justify-between rounded-2xl bg-[#f1f5f9] px-3.5 py-2.5">
                    <span class="sidebar-label text-xs font-bold text-slate-700">Mode Gelap</span>
                    <button type="button" id="theme-toggle-btn" onclick="toggleDarkMode()" role="switch" aria-checked="false" aria-label="Ubah mode gelap" title="Ubah Mode Terang / Gelap" class="theme-switch">
                        <span class="theme-switch-thumb" aria-hidden="true"></span>
                        {{-- Matahari --}}
                        <svg class="icon-sun relative z-10 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="4" />
                            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" />
                        </svg>
                        {{-- Bulan --}}
                        <svg class="icon-moon relative z-10 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                        </svg>
                    </button>
                </div>

                @auth
                    <div class="sidebar-profile-card flex items-center justify-between gap-2.5 rounded-2xl bg-[#f1f5f9] p-3">
                        <div class="sidebar-profile-info flex min-w-0 items-center gap-2.5" title="{{ auth()->user()->name }}">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#084734] text-white shadow-xs">
                                <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="4" />
                                    <path d="M20 21a8 8 0 0 0-16 0" />
                                </svg>
                            </div>
                            <div class="sidebar-label min-w-0">
                                <p class="truncate text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="truncate text-[11px] text-slate-500">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                            @csrf
                            <button type="submit" class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 cursor-pointer" title="Keluar">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" y1="12" x2="9" y2="12" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" title="Masuk Akun" class="sidebar-profile-card flex items-center gap-3 rounded-2xl bg-[#f1f5f9] p-3 transition hover:bg-slate-200/70">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#084734] text-white shadow-xs">
                            <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M20 21a8 8 0 0 0-16 0" />
                            </svg>
                        </div>
                        <div class="sidebar-label min-w-0">
                            <p class="truncate text-xs font-bold text-slate-900">Masuk Akun</p>
                            <p class="truncate text-[11px] text-slate-500">Login / Daftar</p>
                        </div>
                    </a>
                @endauth
            </div>
        </aside>
        <script>
            (function () {
                const sb = document.getElementById('app-sidebar');
                if (localStorage.getItem('dompetku_sidebar_collapsed') === '1') {
                    const tt = document.getElementById('sidebar-toggle-tooltip');
                    const btn = document.getElementById('desktop-sidebar-toggle');
                    if (sb) sb.classList.add('is-collapsed');
                    if (tt) tt.textContent = 'Buka sidebar';
                    if (btn) btn.setAttribute('aria-label', 'Buka sidebar');
                }
                requestAnimationFrame(() => requestAnimationFrame(() => {
                    if (sb) sb.classList.add('sidebar-animated');
                }));
            })();
        </script>

        {{-- Right Content Wrapper --}}
        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Top Header Bar --}}
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200/70 bg-white/95 px-4 backdrop-blur-xs sm:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleMobileSidebar(true)" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden" aria-label="Buka menu">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="inline-flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-1.5 text-sm font-semibold text-slate-700 ring-1 ring-slate-200/70">
                        <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <path d="M16 2v4M8 2v4M3 10h18" />
                        </svg>
                        <span>{{ $displayMonth }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3">
                    <button type="button" onclick="openQuickTransaction()" class="inline-flex items-center gap-1.5 rounded-xl bg-[#084734] px-4 py-2 text-sm font-semibold text-white shadow-xs transition hover:bg-[#063829] cursor-pointer">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        <span>Tambah Transaksi</span>
                    </button>
                </div>
            </header>

            {{-- Flash Status Toast --}}
            @if (session('status'))
                <div class="mx-4 mt-4 sm:mx-8">
                    <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            <span>{{ session('status') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 cursor-pointer">&times;</button>
                    </div>
                </div>
            @endif

            {{-- Main Content --}}
            <main class="flex-1 px-4 py-6 sm:px-8 sm:py-7">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Quick Transaction Modal --}}
    <div id="quick-tx-modal" onclick="if (event.target === this) closeQuickTransactionModal()" class="fixed inset-0 z-50 {{ $errors->any() ? 'flex' : 'hidden' }} items-center justify-center bg-slate-900/45 p-4 backdrop-blur-xs">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold tracking-wider text-emerald-700 uppercase">Formulir Cepat</p>
                    <h3 class="text-lg font-bold text-slate-900">Tambah Transaksi</h3>
                </div>
                <button type="button" onclick="closeQuickTransactionModal()" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 cursor-pointer">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-xs font-medium text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="type" id="modal-tx-type" value="{{ old('type', 'expense') }}">

                <div class="grid grid-cols-2 gap-2 rounded-xl bg-slate-100 p-1">
                    <button type="button" id="modal-btn-income" onclick="setModalTxType('income')" class="rounded-lg py-2 text-sm font-semibold transition cursor-pointer {{ old('type') === 'income' ? 'bg-[#084734] text-white' : 'text-slate-600 hover:text-slate-900' }}">Pemasukan</button>
                    <button type="button" id="modal-btn-expense" onclick="setModalTxType('expense')" class="rounded-lg py-2 text-sm font-semibold transition cursor-pointer {{ old('type', 'expense') === 'expense' ? 'bg-[#084734] text-white' : 'text-slate-600 hover:text-slate-900' }}">Pengeluaran</button>
                </div>

                <div>
                    <label for="modal-tx-title" class="mb-1.5 block text-xs font-bold text-slate-700">Judul Transaksi</label>
                    <input id="modal-tx-title" type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Belanja Bulanan, Gajian..." class="w-full rounded-xl bg-[#edf2f8] px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-[#084734]/20 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="modal-tx-amount" class="mb-1.5 block text-xs font-bold text-slate-700">Nominal (Rp)</label>
                        <input id="modal-tx-amount" type="text" inputmode="numeric" name="amount" value="{{ old('amount') ? number_format((int) preg_replace('/\D+/', '', (string) old('amount')), 0, ',', '.') : '' }}" required placeholder="0" autocomplete="off" class="w-full rounded-xl bg-[#edf2f8] px-3.5 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-[#084734]/20 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-700">Tanggal</label>
                        <input type="date" name="occurred_at" required value="{{ old('occurred_at', now()->format('Y-m-d')) }}" class="w-full rounded-xl bg-[#edf2f8] px-3.5 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#084734]/20 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label for="modal-tx-category" class="mb-1.5 block text-xs font-bold text-slate-700">Kategori</label>
                    <select id="modal-tx-category" name="category" class="w-full rounded-xl bg-[#edf2f8] px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#084734]/20 focus:outline-none">
                    </select>
                </div>

                <button type="submit" class="mt-2 w-full rounded-xl bg-[#084734] py-3 text-sm font-bold tracking-wide text-white shadow-sm transition hover:bg-[#063829] cursor-pointer">
                    SIMPAN TRANSAKSI
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('dompetku_theme', isDark ? 'dark' : 'light');
            const btn = document.getElementById('theme-toggle-btn');
            if (btn) btn.setAttribute('aria-checked', isDark ? 'true' : 'false');
        }

        (function syncThemeSwitch() {
            const btn = document.getElementById('theme-toggle-btn');
            if (btn) {
                btn.setAttribute('aria-checked', document.documentElement.classList.contains('dark') ? 'true' : 'false');
            }
        })();

        function toggleDesktopSidebar() {
            const sidebar = document.getElementById('app-sidebar');
            const tooltip = document.getElementById('sidebar-toggle-tooltip');
            const btn = document.getElementById('desktop-sidebar-toggle');
            if (!sidebar) return;

            const collapsed = sidebar.classList.toggle('is-collapsed');
            localStorage.setItem('dompetku_sidebar_collapsed', collapsed ? '1' : '0');
            const label = collapsed ? 'Buka sidebar' : 'Tutup sidebar';
            if (tooltip) tooltip.textContent = label;
            if (btn) btn.setAttribute('aria-label', label);
        }

        function toggleMobileSidebar(open) {
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (!sidebar || !backdrop) return;
            sidebar.classList.toggle('-translate-x-full', !open);
            backdrop.classList.toggle('hidden', !open);
        }

        function openQuickTransaction() {
            const modal = document.getElementById('quick-tx-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                const titleInput = document.getElementById('modal-tx-title');
                if (titleInput) setTimeout(() => titleInput.focus(), 50);
            }
        }

        function closeQuickTransactionModal() {
            const modal = document.getElementById('quick-tx-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        const MODAL_CATEGORIES = @json($modalCategories);

        function populateModalCategories(type, preferredValue = null) {
            const select = document.getElementById('modal-tx-category');
            if (!select) return;
            const filtered = MODAL_CATEGORIES.filter(c => c.type === type);
            select.innerHTML = '';
            filtered.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.value;
                opt.textContent = c.label;
                select.appendChild(opt);
            });
            if (preferredValue && filtered.some(c => c.value === preferredValue)) {
                select.value = preferredValue;
            }
        }

        function setModalTxType(type, preferredCategory = null) {
            const input = document.getElementById('modal-tx-type');
            const btnExpense = document.getElementById('modal-btn-expense');
            const btnIncome = document.getElementById('modal-btn-income');
            if (!input || !btnExpense || !btnIncome) return;
            input.value = type;
            if (type === 'expense') {
                btnExpense.className = 'rounded-lg bg-[#084734] py-2 text-sm font-semibold text-white transition cursor-pointer';
                btnIncome.className = 'rounded-lg py-2 text-sm font-semibold text-slate-600 transition hover:text-slate-900 cursor-pointer';
            } else {
                btnIncome.className = 'rounded-lg bg-[#084734] py-2 text-sm font-semibold text-white transition cursor-pointer';
                btnExpense.className = 'rounded-lg py-2 text-sm font-semibold text-slate-600 transition hover:text-slate-900 cursor-pointer';
            }
            populateModalCategories(type, preferredCategory);
        }

        function formatThousandDots(raw) {
            const digits = String(raw || '').replace(/\D+/g, '').replace(/^0+(?=\d)/, '');
            return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        (function initQuickTxForm() {
            const initialType = document.getElementById('modal-tx-type')?.value || 'expense';
            const initialCategory = @json(old('category'));
            setModalTxType(initialType, initialCategory);

            const amountInput = document.getElementById('modal-tx-amount');
            if (amountInput) {
                amountInput.addEventListener('input', function () {
                    const prevLen = this.value.length;
                    const cursor = this.selectionStart ?? prevLen;
                    const formatted = formatThousandDots(this.value);
                    this.value = formatted;
                    const diff = formatted.length - prevLen;
                    const nextPos = Math.max(0, cursor + diff);
                    this.setSelectionRange(nextPos, nextPos);
                });
            }
        })();

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeQuickTransactionModal();
        });
    </script>
</body>
</html>
