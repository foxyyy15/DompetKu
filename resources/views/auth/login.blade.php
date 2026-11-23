<x-layouts.auth title="Masuk ke Akun Anda" :showHeaderBrandText="true">
    <div class="w-full max-w-[440px] rounded-3xl border border-slate-100 bg-white px-7 py-9 shadow-[0_20px_60px_-20px_rgba(15,23,42,0.12)] sm:px-9">
        <div class="text-center">
            <div class="mb-4 flex justify-center">
                <x-brand-logo size="md" />
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Masuk ke Akun Anda</h1>
            <p class="mt-2 text-xs leading-relaxed text-slate-500">
                Kelola catatan keuangan, pantau pengeluaran, dan<br class="hidden sm:inline"> atur saldo kas Anda.
            </p>
        </div>

        @if ($errors->any())
            <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-medium text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" class="mt-7 space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-xs font-bold text-slate-800">Email Aktif</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                        </svg>
                    </span>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           placeholder="nama@email.com"
                           class="w-full rounded-xl bg-[#edf2f8] py-3 pr-4 pl-10 text-sm text-slate-800 placeholder-slate-400 transition focus:bg-white focus:ring-2 focus:ring-[#084734]/20 focus:outline-none">
                </div>
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between">
                    <label for="password" class="block text-xs font-bold text-slate-800">Kata Sandi</label>
                    <a href="{{ route('login') }}" onclick="event.preventDefault(); alert('Hubungi admin untuk reset kata sandi akun Anda.');" class="text-xs font-semibold text-teal-700 transition hover:text-teal-800">Lupa Password?</a>
                </div>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </span>
                    <input id="password" name="password" type="password" required
                           placeholder="••••••••••••"
                           class="w-full rounded-xl bg-[#edf2f8] py-3 pr-11 pl-10 text-sm text-slate-800 placeholder-slate-400 transition focus:bg-white focus:ring-2 focus:ring-[#084734]/20 focus:outline-none">
                    <button type="button" onclick="togglePasswordField('password', this)" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-500 transition hover:text-slate-800 cursor-pointer" aria-label="Tampilkan kata sandi">
                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between pt-0.5">
                <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-[#084734] focus:ring-[#084734]">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#084734] px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#063829] cursor-pointer">
                <span>Masuk Sekarang</span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                </svg>
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-slate-500">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-bold text-teal-700 underline decoration-teal-700/60 underline-offset-2 transition hover:text-teal-800">Daftar Sekarang</a>
        </p>
    </div>
</x-layouts.auth>
