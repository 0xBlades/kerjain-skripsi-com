<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Thesis Assistant') }} - AI Research Studio</title>
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .hero-grid {
                background-image: linear-gradient(rgba(148,163,184,0.07) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(148,163,184,0.07) 1px, transparent 1px);
                background-size: 40px 40px;
            }
            .dark .hero-grid {
                background-image: linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
            }
            .glow-sky { box-shadow: 0 0 60px -10px rgba(14,165,233,0.25); }
            .dark .glow-sky { box-shadow: 0 0 80px -10px rgba(255,255,255,0.07); }
            .feature-card { transition: transform 0.2s, box-shadow 0.2s; }
            .feature-card:hover { transform: translateY(-3px); }
        </style>
    </head>
    <body class="min-h-screen landing-body overflow-x-hidden">

        <!-- Navbar -->
        <header class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/80 backdrop-blur-xl dark:bg-[#000]/80 dark:border-[#2a2a2a]">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="h-8 w-8 rounded-lg bg-sky-500 flex items-center justify-center text-white font-bold text-sm">TA</div>
                    <span class="font-semibold text-slate-900 dark:text-[#ededed]">Thesis Assistant</span>
                </div>
                <div class="flex items-center gap-3 text-sm font-medium">
                    <button onclick="toggleLandingTheme()" class="h-8 w-8 flex items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:text-slate-900 transition dark:border-[#2a2a2a] dark:text-[#888] dark:hover:text-[#ededed]">
                        <svg class="h-4 w-4 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg class="h-4 w-4 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-10h-1M4.34 12h-1m14.14-6.36l-.7.7M6.22 17.78l-.7.7m12.02 0l-.7-.7M6.22 6.22l-.7-.7M12 7a5 5 0 100 10 5 5 0 000-10z"/></svg>
                    </button>
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-1.5 rounded-full border border-slate-200 text-slate-700 hover:border-sky-300 hover:text-sky-600 transition dark:border-[#2a2a2a] dark:text-[#ededed]">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-900 transition dark:text-[#888] dark:hover:text-[#ededed]">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-1.5 rounded-full bg-sky-500 text-white hover:bg-sky-600 transition shadow-sm shadow-sky-200 dark:shadow-none">Daftar Gratis</a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero -->
        <section class="hero-grid relative overflow-hidden">
            <!-- radial fade overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-white/0 via-white/0 to-white pointer-events-none dark:from-black/0 dark:via-black/0 dark:to-black"></div>

            <div class="relative max-w-6xl mx-auto px-6 pt-24 pb-24 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-sky-200 bg-sky-50 text-sky-600 text-xs font-medium mb-8 dark:border-[#2a2a2a] dark:bg-white/5 dark:text-[#ededed]">
                    <span class="h-1.5 w-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                    AI Research Studio untuk Mahasiswa
                </div>
                <h1 class="text-5xl lg:text-7xl font-bold leading-[1.08] tracking-tight text-slate-900 dark:text-[#ededed] max-w-4xl mx-auto">
                    Selesaikan skripsi<br>
                    <span class="text-sky-500 dark:text-white">tanpa deadlock.</span>
                </h1>
                <p class="mt-6 text-lg text-slate-500 dark:text-[#888] max-w-2xl mx-auto leading-relaxed">
                    Cari referensi, susun bab, koreksi typo, cek plagiarisme, temukan research gap — semua dalam satu workspace yang paham ritme mahasiswa Indonesia.
                </p>
                <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('register') }}" class="px-6 py-3 rounded-full bg-sky-500 text-white font-semibold hover:bg-sky-600 transition shadow-lg shadow-sky-200/60 dark:shadow-none dark:bg-white dark:text-black dark:hover:bg-[#ededed]">
                        Mulai Gratis
                    </a>
                    <a href="#fitur" class="px-6 py-3 rounded-full border border-slate-200 text-slate-700 hover:border-sky-300 hover:text-sky-600 transition dark:border-[#2a2a2a] dark:text-[#888] dark:hover:text-[#ededed] dark:hover:border-[#444]">
                        Lihat Fitur &darr;
                    </a>
                </div>

                <!-- Stats row -->
                <div class="mt-16 grid grid-cols-3 gap-6 max-w-lg mx-auto">
                    @foreach([['450+', 'referensi kurasi'], ['92%', 'selesai on-time'], ['7.8%', 'similarity rata-rata']] as [$num, $label])
                    <div class="text-center">
                        <p class="text-2xl font-bold text-slate-900 dark:text-[#ededed]">{{ $num }}</p>
                        <p class="text-xs text-slate-400 dark:text-[#666] mt-0.5">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Preview Card -->
        <section class="max-w-6xl mx-auto px-6 -mt-4 mb-28 relative z-10">
            <div class="glow-sky rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-xl dark:bg-[#111111] dark:border-[#2a2a2a]">
                <!-- Mock browser bar -->
                <div class="flex items-center gap-1.5 px-4 py-3 border-b border-slate-100 bg-slate-50 dark:bg-[#0a0a0a] dark:border-[#2a2a2a]">
                    <div class="h-3 w-3 rounded-full bg-red-400"></div>
                    <div class="h-3 w-3 rounded-full bg-yellow-400"></div>
                    <div class="h-3 w-3 rounded-full bg-green-400"></div>
                    <div class="ml-3 flex-1 bg-white dark:bg-[#1a1a1a] rounded-md px-3 py-1 text-xs text-slate-400 dark:text-[#555] border border-slate-200 dark:border-[#2a2a2a] max-w-xs">
                        thesis-assistant.app/dashboard
                    </div>
                </div>
                <!-- Mock dashboard content -->
                <div class="p-6 grid md:grid-cols-3 gap-4">
                    <div class="md:col-span-2 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-slate-400 dark:text-[#666] uppercase tracking-wider">Skripsi Aktif</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-[#ededed] mt-0.5">Pengaruh Digital Marketing terhadap UMKM</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-sky-50 text-sky-600 border border-sky-100 dark:bg-white/10 dark:text-white dark:border-white/10">In Progress</span>
                        </div>
                        <div class="space-y-3">
                            @foreach(['Bab I — Pendahuluan' => 100, 'Bab II — Tinjauan Pustaka' => 78, 'Bab III — Metodologi' => 45, 'Bab IV — Analisis' => 12] as $bab => $pct)
                            <div>
                                <div class="flex justify-between text-xs text-slate-500 dark:text-[#666] mb-1">
                                    <span>{{ $bab }}</span><span>{{ $pct }}%</span>
                                </div>
                                <div class="h-1.5 rounded-full bg-slate-100 dark:bg-[#2a2a2a] overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-cyan-400" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-3">
                        @foreach([['⚡', 'AI Writing', 'Generate outline Bab II', 'sky'], ['✓', 'Plagiarisme', 'Similarity 7.8% — Aman', 'green'], ['🔍', 'Research Gap', '3 gap ditemukan', 'purple']] as [$icon, $title, $desc, $color])
                        <div class="p-3 rounded-xl border border-slate-100 dark:border-[#2a2a2a] bg-slate-50/50 dark:bg-[#1a1a1a]">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm">{{ $icon }}</span>
                                <span class="text-xs font-semibold text-slate-700 dark:text-[#d1d1d1]">{{ $title }}</span>
                            </div>
                            <p class="text-xs text-slate-400 dark:text-[#666]">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="fitur" class="max-w-6xl mx-auto px-6 py-16">
            <div class="text-center mb-12">
                <p class="text-xs uppercase tracking-[0.4em] text-sky-500 dark:text-[#888] mb-3">Fitur Unggulan</p>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 dark:text-[#ededed]">Semua yang kamu butuhkan,<br>dalam satu tempat</h2>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach([
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'label' => 'AI Writing', 'title' => 'Generate & Perbaiki Teks', 'desc' => 'Buat outline, konten bab, abstrak, dan pendahuluan dengan AI. Support Bahasa Indonesia & English.'],
                    ['icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'label' => 'Koreksi Typo', 'title' => 'Koreksi Otomatis', 'desc' => 'Deteksi dan perbaiki kesalahan ejaan, tanda baca, dan tata bahasa secara instan.'],
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Plagiarisme', 'title' => 'Cek Similarity', 'desc' => 'Analisis kesamaan teks dan dapatkan rekomendasi parafrase untuk menurunkan similarity score.'],
                    ['icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'label' => 'Research Gap', 'title' => 'Temukan Gap Riset', 'desc' => 'Identifikasi celah penelitian berdasarkan topik skripsi dan tren literatur terkini.'],
                    ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Cari Paper', 'title' => 'Pencarian Referensi', 'desc' => 'Temukan jurnal dari Semantic Scholar, filter berdasarkan tahun, bahasa, dan relevansi.'],
                    ['icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Manajemen', 'title' => 'Kelola Dokumen', 'desc' => 'Simpan draft bab, upload dokumen pendukung, dan pantau progress skripsi dalam satu dashboard.'],
                ] as $f)
                <div class="feature-card p-5 rounded-2xl border border-slate-200 bg-white dark:bg-[#111111] dark:border-[#2a2a2a]">
                    <div class="h-9 w-9 rounded-xl bg-sky-50 dark:bg-white/5 flex items-center justify-center mb-4">
                        <svg class="h-5 w-5 text-sky-500 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $f['icon'] }}" />
                        </svg>
                    </div>
                    <p class="text-xs uppercase tracking-wider text-slate-400 dark:text-[#555] mb-1.5">{{ $f['label'] }}</p>
                    <h3 class="font-semibold text-slate-900 dark:text-[#ededed] mb-2">{{ $f['title'] }}</h3>
                    <p class="text-sm text-slate-500 dark:text-[#888] leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <!-- How it works -->
        <section class="max-w-6xl mx-auto px-6 py-16">
            <div class="rounded-2xl border border-slate-200 bg-white dark:bg-[#111111] dark:border-[#2a2a2a] overflow-hidden">
                <div class="grid lg:grid-cols-2">
                    <div class="p-10 lg:p-12 border-b lg:border-b-0 lg:border-r border-slate-100 dark:border-[#2a2a2a]">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400 dark:text-[#555] mb-4">Cara Kerja</p>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-[#ededed] mb-8">Tiga langkah dari topik ke submit</h2>
                        <ol class="space-y-6">
                            @foreach([
                                ['01', 'Buat profil skripsi', 'Input tema, rumusan masalah, dan target bab - AI akan menyesuaikan seluruh output dengannya.'],
                                ['02', 'Gunakan fitur AI', 'Generate teks, koreksi typo, cek plagiarisme, dan temukan research gap dalam satu workflow.'],
                                ['03', 'Export & submit', 'Simpan semua draft dan dokumen, pantau progress, siap untuk bimbingan.'],
                            ] as [$num, $title, $desc])
                            <li class="flex gap-4">
                                <span class="text-xs font-mono font-bold text-slate-300 dark:text-[#444] pt-0.5 shrink-0">{{ $num }}</span>
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-[#ededed] mb-1">{{ $title }}</p>
                                    <p class="text-sm text-slate-500 dark:text-[#888]">{{ $desc }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                    <div class="p-10 lg:p-12 flex flex-col gap-5">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400 dark:text-[#555] mb-2">Kenapa Thesis Assistant?</p>
                        @foreach([
                            ['AI', 'AI dengan konteks skripsi', 'Setiap prompt memahami judul, bidang, dan rumusan masalah yang sudah kamu isi.'],
                            ['ID', 'Dirancang untuk Indonesia', 'Support Bahasa Indonesia, format APA/IEEE, dan standar kampus lokal.'],
                            ['*', 'Privasi terjaga', 'Data skripsimu tidak digunakan untuk melatih model AI manapun.'],
                            ['>', 'Cepat & ringkas', 'Tidak perlu pindah-pindah tab. Semua fitur dalam satu halaman.'],
                        ] as [$icon, $title, $desc])
                        <div class="flex gap-3">
                            <span class="text-xl shrink-0 mt-0.5">{{ $icon }}</span>
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-[#ededed] text-sm">{{ $title }}</p>
                                <p class="text-sm text-slate-500 dark:text-[#888] mt-0.5">{{ $desc }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="max-w-6xl mx-auto px-6 py-16">
            <div class="rounded-2xl bg-sky-500 dark:bg-white px-8 py-14 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(rgba(255,255,255,0.15) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.15) 1px, transparent 1px); background-size: 30px 30px;"></div>
                <div class="relative">
                    <h2 class="text-3xl font-bold text-white dark:text-black mb-3">Mulai sekarang, gratis.</h2>
                    <p class="text-sky-100 dark:text-[#555] mb-8 max-w-md mx-auto">Buat akun dan langsung akses semua fitur AI tanpa biaya apapun.</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-white dark:bg-black text-sky-600 dark:text-white font-semibold hover:bg-sky-50 dark:hover:bg-[#111] transition shadow-lg">
                        Buat Akun Gratis
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-slate-100 dark:border-[#2a2a2a]">
            <div class="max-w-6xl mx-auto px-6 py-8 flex flex-wrap items-center justify-between gap-4 text-sm text-slate-400 dark:text-[#555]">
                <div class="flex items-center gap-2">
                    <div class="h-6 w-6 rounded bg-sky-500 flex items-center justify-center text-white text-xs font-bold">TA</div>
                    <span>&copy; {{ date('Y') }} Thesis Assistant</span>
                </div>
                <div class="flex gap-6">
                    <span class="hover:text-slate-600 dark:hover:text-[#888] cursor-pointer transition">Tentang</span>
                    <span class="hover:text-slate-600 dark:hover:text-[#888] cursor-pointer transition">Privasi</span>
                    <span class="hover:text-slate-600 dark:hover:text-[#888] cursor-pointer transition">Kontak</span>
                </div>
            </div>
        </footer>

        <script>
            function toggleLandingTheme() {
                const root = document.documentElement;
                const isDark = root.classList.toggle('dark');
                localStorage.theme = isDark ? 'dark' : 'light';
            }
        </script>
    </body>
</html>
