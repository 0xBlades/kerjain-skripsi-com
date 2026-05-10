<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900 dark:text-white">
                    AI Writing Studio
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Asisten AI untuk menulis, koreksi, cek plagiarisme, dan analisis research gap.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3 py-1 text-xs font-medium text-sky-600 dark:bg-sky-950/50 dark:text-sky-200">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Powered by AI
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Sub-menu Pill Tabs -->
            <div class="feature-surface p-1.5">
                <nav class="flex gap-1" aria-label="AI Writing sub-menu">
                    <button onclick="switchTab('writing')" id="tab-writing"
                        class="tab-btn active-tab flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="hidden sm:inline">AI Writing</span>
                        <span class="sm:hidden">Writing</span>
                    </button>
                    <button onclick="switchTab('typo')" id="tab-typo"
                        class="tab-btn inactive-tab flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="hidden sm:inline">Koreksi Typo</span>
                        <span class="sm:hidden">Typo</span>
                    </button>
                    <button onclick="switchTab('gap')" id="tab-gap"
                        class="tab-btn inactive-tab flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="hidden sm:inline">Research Gap</span>
                        <span class="sm:hidden">Gap</span>
                    </button>
                </nav>
            </div>

            <!-- Feature Panels -->
            <div class="feature-surface overflow-hidden">

                <!-- AI Writing Tab -->
                <div id="panel-writing" class="tab-panel p-6 space-y-6">
                    <div class="grid lg:grid-cols-3 gap-6">
                        <!-- Input Section -->
                        <div class="lg:col-span-2 space-y-4">
                            <div class="flex flex-wrap gap-3">
                                <select id="writingThesis" class="rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-2.5 text-sm focus:border-sky-400 focus:ring-sky-400 transition w-auto min-w-[180px]">
                                    <option value="">-- Pilih Skripsi --</option>
                                    @foreach($theses as $thesis)
                                        <option value="{{ $thesis->id }}">{{ $thesis->title }}</option>
                                    @endforeach
                                </select>
                                <select id="writingType" class="rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-2.5 text-sm focus:border-sky-400 focus:ring-sky-400 transition w-auto min-w-[140px]">
                                    <option value="outline">Outline Bab</option>
                                    <option value="chapter">Konten Bab</option>
                                    <option value="abstract">Abstrak</option>
                                    <option value="introduction">Pendahuluan</option>
                                    <option value="literature_review">Tinjauan Pustaka</option>
                                    <option value="methodology">Metodologi</option>
                                    <option value="analysis">Analisis</option>
                                    <option value="conclusion">Kesimpulan</option>
                                </select>
                                <select id="writingLanguage" class="rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-2.5 text-sm focus:border-sky-400 focus:ring-sky-400 transition w-auto min-w-[140px]">
                                    <option value="mixed">Bahasa Campur</option>
                                    <option value="id">Bahasa Indonesia</option>
                                    <option value="en">English</option>
                                </select>
                                <select id="writingWords" class="rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-2.5 text-sm focus:border-sky-400 focus:ring-sky-400 transition w-auto min-w-[120px]">
                                    <option value="300">~300 kata</option>
                                    <option value="500" selected>~500 kata</option>
                                    <option value="800">~800 kata</option>
                                    <option value="1000">~1000 kata</option>
                                    <option value="1500">~1500 kata</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-2">Prompt / Petunjuk</label>
                                <textarea id="writingPrompt" rows="6" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-3 text-sm focus:bg-white focus:border-sky-400 focus:ring-sky-400 transition" placeholder="Contoh: Buatkan outline Bab II tentang dampak digital marketing terhadap UMKM di Indonesia dengan fokus pada aspek penjualan dan branding..."></textarea>
                            </div>

                            <div class="flex gap-3">
                                <button onclick="generateContent()" id="btnGenerate" class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-200 hover:bg-sky-600 disabled:opacity-50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Generate
                                </button>
                                <button onclick="improveText()" id="btnImprove" class="inline-flex items-center gap-2 rounded-xl border border-slate-200/70 bg-white/80 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-white disabled:opacity-50 dark:border-[#2a2a2a] dark:bg-[#111111]/80 dark:text-[#ededed] dark:hover:bg-[#1a1a1a]">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    Perbaiki
                                </button>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="space-y-4">
                            <h3 class="font-semibold text-slate-900 dark:text-white">Template Cepat</h3>
                            <div class="space-y-2">
                                <button onclick="applyTemplate('outline_latarbelakang')" class="feature-muted w-full text-left p-3 rounded-xl hover:bg-sky-50/50 dark:hover:bg-white/5 transition-colors text-sm">
                                    <span class="font-medium text-slate-900 dark:text-white">Outline Latar Belakang</span>
                                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1">Struktur lengkap dengan identifikasi masalah</p>
                                </button>
                                <button onclick="applyTemplate('tinjauan_pustaka')" class="feature-muted w-full text-left p-3 rounded-xl hover:bg-sky-50/50 dark:hover:bg-white/5 transition-colors text-sm">
                                    <span class="font-medium text-slate-900 dark:text-white">Tinjauan Pustaka</span>
                                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1">Format dengan tabel studi terdahulu</p>
                                </button>
                                <button onclick="applyTemplate('metodologi_mixed')" class="feature-muted w-full text-left p-3 rounded-xl hover:bg-sky-50/50 dark:hover:bg-white/5 transition-colors text-sm">
                                    <span class="font-medium text-slate-900 dark:text-white">Metodologi Mixed Method</span>
                                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1">Kombinasi kuantitatif & kualitatif</p>
                                </button>
                                <button onclick="applyTemplate('bab_analisis')" class="feature-muted w-full text-left p-3 rounded-xl hover:bg-sky-50/50 dark:hover:bg-white/5 transition-colors text-sm">
                                    <span class="font-medium text-slate-900 dark:text-white">Bab Analisis Data</span>
                                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1">Struktur pembahasan hasil penelitian</p>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Output -->
                    <div id="writingOutput" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-slate-800 pb-3">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-200 dark:shadow-none">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-slate-900 dark:text-white">Hasil Draft Akademik</h3>
                            </div>
                            <div class="flex items-center gap-2">
                                <span id="wordCountInfo" class="text-[10px] uppercase tracking-wider font-bold text-slate-400 bg-slate-50 dark:bg-white/5 px-2 py-1 rounded-md"></span>
                                <div class="h-4 w-[1px] bg-slate-200 dark:bg-slate-800 mx-1"></div>
                                <button onclick="copyOutput('writing')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-sky-500 transition-colors dark:text-slate-400 dark:hover:text-sky-400">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                    Copy
                                </button>
                                <button onclick="downloadAsTxt()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-sky-500 transition-colors dark:text-slate-400 dark:hover:text-sky-400">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </button>
                            </div>
                        </div>
                        <div class="relative group">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-sky-500/20 to-indigo-500/20 rounded-2xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                            <div class="relative bg-white dark:bg-[#0d0d0d] border border-slate-100 dark:border-neutral-800/60 rounded-2xl p-8 shadow-sm">
                                <div id="writingResult" class="prose prose-slate max-w-none dark:prose-invert prose-headings:font-display prose-headings:font-bold prose-p:leading-relaxed prose-p:text-slate-600 dark:prose-p:text-neutral-400 prose-a:text-sky-500">
                                    <!-- Markdown content will be rendered here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Typo Correction Tab -->
                <div id="panel-typo" class="tab-panel hidden p-6 space-y-6">
                    <div class="grid lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teks Input</label>
                            <textarea id="typoInput" rows="12" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 focus:bg-white focus:border-sky-400 focus:ring-sky-400 transition" placeholder="Paste teks yang ingin dikoreksi..."></textarea>
                            <div class="mt-4 flex gap-3">
                                <select id="typoLanguage" class="rounded-2xl border border-slate-200/70 text-sm px-4 py-2 bg-white/90 focus:border-sky-400 focus:ring-sky-400 transition">
                                    <option value="mixed">Deteksi Otomatis</option>
                                    <option value="id">Bahasa Indonesia</option>
                                    <option value="en">English</option>
                                </select>
                                <button onclick="correctTypo()" class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-5 py-2 text-sm font-semibold text-white hover:bg-sky-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    Koreksi
                                </button>
                            </div>
                        </div>
                        <div id="typoOutput" class="hidden">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Hasil Koreksi</label>
                            <div class="feature-surface p-4 space-y-4">
                                <div id="correctedText" class="text-slate-900 dark:text-slate-100 whitespace-pre-wrap"></div>
                                <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                                    <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Detail Koreksi</h4>
                                    <ul id="correctionsList" class="space-y-2 text-sm"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Research Gap Tab -->
                <div id="panel-gap" class="tab-panel hidden p-6 space-y-6">
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-3">
                            <select id="gapThesis" class="rounded-2xl border border-slate-200/70 text-sm px-4 py-2 bg-white/90 focus:border-sky-400 focus:ring-sky-400 transition">
                                <option value="">-- Pilih Skripsi --</option>
                                @foreach($theses as $thesis)
                                    <option value="{{ $thesis->id }}">{{ $thesis->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Topik Spesifik</label>
                            <input type="text" id="gapTopic" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 focus:border-sky-400 focus:ring-sky-400 transition" placeholder="Contoh: Dampak social media marketing terhadap brand awareness UMKM">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Rumusan Masalah (opsional)</label>
                            <textarea id="gapQuestions" rows="3" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 focus:bg-white focus:border-sky-400 focus:ring-sky-400 transition" placeholder="1. Bagaimana pengaruh X terhadap Y?&#10;2. Apa faktor yang mempengaruhi Z?"></textarea>
                        </div>
                        <button onclick="findGap()" class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-200 hover:bg-sky-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Analisis Research Gap
                        </button>
                    </div>

                    <div id="gapOutput" class="hidden space-y-4">
                        <h3 class="font-semibold text-slate-900 dark:text-white">Research Gap Ditemukan</h3>
                        <div id="gapResults" class="grid gap-4"></div>
                        <div id="gapRecommendation" class="feature-muted p-4"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            @if($recentInteractions->count() > 0)
            <div class="feature-surface p-6">
                <h3 class="font-semibold text-slate-900 dark:text-white mb-4">Aktivitas Terbaru</h3>
                <div class="grid gap-3">
                    @foreach($recentInteractions as $interaction)
                    <div class="feature-muted flex items-center justify-between p-3">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-sky-100/70 dark:bg-white/10 flex items-center justify-center">
                                <svg class="h-4 w-4 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    @if($interaction->feature == 'ai_writing')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    @elseif($interaction->feature == 'typo_correction')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white text-sm">{{ ucfirst(str_replace('_', ' ', $interaction->feature)) }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $interaction->created_at->diffForHumans() }} · {{ round($interaction->duration_ms) }}ms</p>
                            </div>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $interaction->status == 'success' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $interaction->status }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        // Tab switching
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active-tab');
                btn.classList.add('inactive-tab');
            });
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));

            const activeBtn = document.getElementById('tab-' + tab);
            activeBtn.classList.remove('inactive-tab');
            activeBtn.classList.add('active-tab');
            document.getElementById('panel-' + tab).classList.remove('hidden');
        }

        // Templates
        function applyTemplate(template) {
            const templates = {
                outline_latarbelakang: `Buatkan outline Bab I (Pendahuluan) dengan struktur:\n1. Latar Belakang (identifikasi masalah, rumusan masalah)\n2. Rumusan Masalah (3-5 pertanyaan penelitian)\n3. Tujuan Penelitian\n4. Manfaat Penelitian (teoritis dan praktis)\n5. Batasan Masalah`,
                tinjauan_pustaka: `Buatkan Tinjauan Pustaka (Bab II) dengan format:\n1. Landasan Teori (konsep-konsep utama)\n2. Penelitian Terdahulu (tabel perbandingan minimal 5 studi)\n3. Kerangka Pemikiran (diagram/logika hubungan variabel)\n4. Hipotesis Penelitian`,
                metodologi_mixed: `Buatkan Metodologi Penelitian (Bab III) dengan:\n1. Jenis dan Pendekatan Penelitian (Mixed Method)\n2. Lokasi dan Waktu Penelitian\n3. Populasi dan Sampel\n4. Teknik Pengumpulan Data (kuesioner, wawancara, observasi)\n5. Uji Validitas dan Reliabilitas\n6. Teknik Analisis Data (kuantitatif dan kualitatif)`,
                bab_analisis: `Buatkan struktur Bab IV (Hasil dan Pembahasan):\n1. Gambaran Umum Responden/Objek\n2. Analisis Deskriptif (statistik dasar)\n3. Uji Asumsi (normalitas, homogenitas)\n4. Uji Hipotesis (uji t/ANOVA/regresi)\n5. Pembahasan (interpretasi hasil dan perbandingan dengan studi terdahulu)`
            };
            
            document.getElementById('writingPrompt').value = templates[template];
        }

        // AI Writing
        async function generateContent() {
            const thesisId = document.getElementById('writingThesis').value;
            const prompt = document.getElementById('writingPrompt').value;
            const type = document.getElementById('writingType').value;
            const language = document.getElementById('writingLanguage').value;
            const wordCount = document.getElementById('writingWords').value;
            
            if (!thesisId || !prompt) {
                alert('Pilih skripsi dan isi prompt terlebih dahulu.');
                return;
            }

            const btn = document.getElementById('btnGenerate');
            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating...`;

            try {
                const response = await fetch('{{ route("ai-writing.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ thesis_id: thesisId, prompt, type, language, word_count: parseInt(wordCount) })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const resultContainer = document.getElementById('writingResult');
                    const rawContent = data.data.content;
                    
                    // Store raw content for copying
                    resultContainer.dataset.raw = rawContent;
                    
                    // Render Markdown
                    resultContainer.innerHTML = marked.parse(rawContent);
                    
                    document.getElementById('wordCountInfo').textContent = `${data.data.word_count_estimate} KATA`;
                    document.getElementById('writingOutput').classList.remove('hidden');
                } else {
                    alert(data.message || 'Gagal generate konten.');
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg> Generate`;
            }
        }

        async function improveText() {
            const text = document.getElementById('writingPrompt').value;
            if (!text || text.length < 50) {
                alert('Masukkan teks minimal 50 karakter untuk diperbaiki.');
                return;
            }
            
            const thesisId = document.getElementById('writingThesis').value || null;
            
            const btn = document.getElementById('btnImprove');
            btn.disabled = true;
            btn.innerHTML = 'Memperbaiki...';

            try {
                const response = await fetch('{{ route("ai-writing.improve") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        thesis_id: thesisId, 
                        text, 
                        improvement_type: 'enhance',
                        language: document.getElementById('writingLanguage').value 
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const resultContainer = document.getElementById('writingResult');
                    const rawContent = data.data.improved_text;
                    
                    // Store raw content for copying
                    resultContainer.dataset.raw = rawContent;
                    
                    // Render Markdown
                    resultContainer.innerHTML = marked.parse(rawContent);
                    
                    document.getElementById('wordCountInfo').textContent = 'DIPERBAIKI';
                    document.getElementById('writingOutput').classList.remove('hidden');
                } else {
                    alert(data.message || 'Gagal memperbaiki teks.');
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg> Perbaiki`;
            }
        }

        // Typo Correction
        async function correctTypo() {
            const text = document.getElementById('typoInput').value;
            if (!text || text.length < 10) {
                alert('Masukkan teks minimal 10 karakter.');
                return;
            }
            
            try {
                const response = await fetch('{{ route("ai-writing.correct-typo") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ text, language: document.getElementById('typoLanguage').value })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('correctedText').textContent = data.data.corrected_text;
                    
                    const list = document.getElementById('correctionsList');
                    list.innerHTML = '';
                    (data.data.corrections || []).forEach(c => {
                        list.innerHTML += `<li class="flex items-center gap-2"><span class="text-red-500 line-through">${c.original}</span> → <span class="text-green-600 font-medium">${c.corrected}</span> <span class="text-xs text-slate-400">(${c.type})</span></li>`;
                    });
                    
                    document.getElementById('typoOutput').classList.remove('hidden');
                } else {
                    alert(data.message || 'Gagal mengoreksi teks.');
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        }

        // Research Gap
        async function findGap() {
            const thesisId = document.getElementById('gapThesis').value;
            const topic = document.getElementById('gapTopic').value;
            const questions = document.getElementById('gapQuestions').value;
            
            if (!thesisId || !topic) {
                alert('Pilih skripsi dan isi topik terlebih dahulu.');
                return;
            }
            
            try {
                const response = await fetch('{{ route("ai-writing.find-gap") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ thesis_id: thesisId, topic, research_questions: questions })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const container = document.getElementById('gapResults');
                    container.innerHTML = '';
                    (data.data.gaps || []).forEach((g, i) => {
                        container.innerHTML += `
                            <div class="feature-surface bg-white rounded-xl border border-slate-200 p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-semibold text-slate-900 dark:text-white">${i + 1}. ${g.title}</h4>
                                    <span class="text-xs px-2 py-1 rounded-full ${g.priority === 'high' ? 'bg-red-100 text-red-600' : g.priority === 'medium' ? 'bg-yellow-100 text-yellow-600' : 'bg-slate-100 text-slate-600'}">${g.priority}</span>
                                </div>
                                <div class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                    <p><span class="font-medium">Existing Research:</span> ${g.existing_research}</p>
                                    <p><span class="font-medium">The Gap:</span> ${g.the_gap}</p>
                                    <p><span class="font-medium">Significance:</span> ${g.significance}</p>
                                    <p><span class="font-medium">Suggested Method:</span> ${g.suggested_method}</p>
                                </div>
                            </div>
                        `;
                    });
                    
                    document.getElementById('gapRecommendation').innerHTML = `<p class="text-sky-800 dark:text-sky-200 font-medium">Rekomendasi: ${data.data.recommendation}</p>`;
                    document.getElementById('gapOutput').classList.remove('hidden');
                } else {
                    alert(data.message || 'Gagal menganalisis research gap.');
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        }

        // Utilities
        function copyOutput(type) {
            const container = document.getElementById(type + 'Result');
            const text = container.dataset.raw || container.textContent;
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-2xl animate-in slide-in-from-bottom-2 z-50';
                toast.textContent = 'Teks berhasil disalin!';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2000);
            });
        }

        function downloadAsTxt() {
            const container = document.getElementById('writingResult');
            const text = container.dataset.raw || container.textContent;
            const blob = new Blob([text], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'draft-skripsi.txt';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
    @endpush
</x-app-layout>
