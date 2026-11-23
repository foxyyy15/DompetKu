<x-layouts.auth title="Buat Akun Baru" :showHeaderBrandText="false">
    <div class="w-full max-w-[520px] rounded-3xl border border-slate-100 bg-white px-7 py-9 shadow-[0_20px_60px_-20px_rgba(15,23,42,0.12)] sm:px-10">
        <div class="text-center">
            <div class="mb-4 flex justify-center">
                <x-brand-logo size="lg" />
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-[28px]">Buat Akun Baru</h1>
            <p class="mt-2 text-xs leading-relaxed text-slate-500 sm:text-sm">
                Mulai catat transaksi harian, analisis grafik, dan kelola anggaran<br class="hidden sm:inline"> pribadi Anda dengan mudah.
            </p>
        </div>

        @if ($errors->any())
            <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-medium text-rose-700">
                <ul class="list-disc space-y-1 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="mt-7 space-y-4">
            @csrf

            <div>
                <label for="name" class="mb-1.5 block text-xs font-bold text-slate-800">Nama Lengkap</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M20 21a8 8 0 0 0-16 0" />
                        </svg>
                    </span>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                           placeholder="Contoh: Lisa Blackpink"
                           class="w-full rounded-xl bg-[#edf2f8] py-3 pr-4 pl-10 text-sm text-slate-800 placeholder-slate-400 transition focus:bg-white focus:ring-2 focus:ring-[#084734]/20 focus:outline-none">
                </div>
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-xs font-bold text-slate-800">Email Aktif</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                        </svg>
                    </span>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                           placeholder="nama@email.com"
                           class="w-full rounded-xl bg-[#edf2f8] py-3 pr-4 pl-10 text-sm text-slate-800 placeholder-slate-400 transition focus:bg-white focus:ring-2 focus:ring-[#084734]/20 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="reg_password" class="mb-1.5 block text-xs font-bold text-slate-800">Kata Sandi</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </span>
                        <input id="reg_password" name="password" type="password" required minlength="8"
                               placeholder="Minimal 8 karakter"
                               class="w-full rounded-xl bg-[#edf2f8] py-3 pr-10 pl-10 text-sm text-slate-800 placeholder-slate-400 transition focus:bg-white focus:ring-2 focus:ring-[#084734]/20 focus:outline-none">
                        <button type="button" onclick="togglePasswordField('reg_password', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-800 cursor-pointer" aria-label="Tampilkan kata sandi">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-xs font-bold text-slate-800">Konfirmasi Sandi</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 1 0 3-6.7L3 8" />
                                <path d="M3 3v5h5" />
                                <path d="M12 7v5l3 2" />
                            </svg>
                        </span>
                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8"
                               placeholder="Ulangi kata sandi"
                               class="w-full rounded-xl bg-[#edf2f8] py-3 pr-10 pl-10 text-sm text-slate-800 placeholder-slate-400 transition focus:bg-white focus:ring-2 focus:ring-[#084734]/20 focus:outline-none">
                        <button type="button" onclick="togglePasswordField('password_confirmation', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-800 cursor-pointer" aria-label="Tampilkan konfirmasi kata sandi">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-1">
                <label class="inline-flex items-start gap-2.5 text-xs text-slate-600 cursor-pointer">
                    <input type="checkbox" name="terms" value="1" required {{ old('terms') ? 'checked' : '' }} class="mt-0.5 h-4 w-4 rounded border-slate-300 text-[#084734] focus:ring-[#084734]">
                    <span>
                        Saya menyetujui <span class="font-semibold text-teal-700">Syarat &amp; Ketentuan</span> serta <span class="font-semibold text-teal-700">Kebijakan Privasi</span> DompetKu.
                    </span>
                </label>
            </div>

            <button type="submit" class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#084734] px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#063829] cursor-pointer">
                <span>Daftar Akun Baru</span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                </svg>
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-slate-500">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-bold text-slate-900 transition hover:text-[#084734]">Masuk di Sini</a>
        </p>
    </div>
</x-layouts.auth>
