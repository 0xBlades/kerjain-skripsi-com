<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900 dark:text-white">
                    Cari Paper & Referensi
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Temukan jurnal dan paper relevan dari Semantic Scholar, Garuda, dan DOAJ.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3 py-1 text-xs font-medium text-sky-600 dark:bg-sky-950/50 dark:text-sky-200">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Semantic Scholar API
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Search Section -->
            <div class="feature-surface p-6">
                <form id="searchForm" class="space-y-6">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                        <div class="flex-1">
                            <label for="query" class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-2">
                                Kata Kunci Pencarian
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="query" 
                                    name="query"
                                    class="block w-full h-12 rounded-2xl border border-slate-200/70 bg-white/90 pl-12 pr-4 text-slate-900 focus:border-sky-400 focus:ring-sky-400 focus:bg-white transition-all shadow-[inset_0_1px_0_rgba(255,255,255,0.6)]"
                                    placeholder="Masukkan topik, judul, atau nama penulis..."
                                    required
                                >
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="w-full lg:w-48">
                            <label for="limit" class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-2">
                                Jumlah Hasil
                            </label>
                            <select 
                                id="limit" 
                                name="limit"
                                class="block w-full h-12 rounded-2xl border border-slate-200/70 bg-white/90 px-4 text-slate-900 focus:border-sky-400 focus:ring-sky-400 transition shadow-[inset_0_1px_0_rgba(255,255,255,0.6)]"
                            >
                                <option value="10">10 hasil</option>
                                <option value="20" selected>20 hasil</option>
                                <option value="50">50 hasil</option>
                                <option value="100">100 hasil</option>
                            </select>
                        </div>
                        <button 
                            type="submit"
                            id="searchButton"
                            class="h-12 inline-flex items-center justify-center gap-2 rounded-full bg-sky-500 px-8 text-sm font-semibold text-white shadow-lg shadow-sky-200 hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span id="searchButtonText">Cari Paper</span>
                        </button>
                    </div>

                    <!-- Advanced Options -->
                    <div class="feature-muted flex flex-wrap gap-x-5 gap-y-2 items-center p-3">
                        <!-- Year Filter -->
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">Tahun</span>
                        <label class="inline-flex items-center gap-1.5 text-sm text-slate-600 dark:text-slate-300 cursor-pointer h-9">
                            <input type="radio" name="year_filter" value="" checked class="rounded-full border-slate-300 text-sky-500 focus:ring-sky-500">
                            Semua
                        </label>
                        <label class="inline-flex items-center gap-1.5 text-sm text-slate-600 dark:text-slate-300 cursor-pointer h-9">
                            <input type="radio" name="year_filter" value="2021" class="rounded-full border-slate-300 text-sky-500 focus:ring-sky-500">
                            5 Tahun Terakhir
                        </label>
                        <label class="inline-flex items-center gap-1.5 text-sm text-slate-600 dark:text-slate-300 cursor-pointer h-9">
                            <input type="radio" name="year_filter" value="2016" class="rounded-full border-slate-300 text-sky-500 focus:ring-sky-500">
                            10 Tahun Terakhir
                        </label>

                        <div class="w-px h-7 bg-slate-200 dark:bg-[#2a2a2a] hidden sm:block mx-1"></div>

                        <!-- Open Access -->
                        <label class="inline-flex items-center gap-1.5 text-sm text-slate-600 dark:text-slate-300 cursor-pointer h-9 select-none">
                            <input type="checkbox" id="openAccessFilter" name="open_access" class="rounded border-slate-300 text-sky-500 focus:ring-sky-500">
                            Open Access
                        </label>

                        <!-- Top Cited -->
                        <label class="inline-flex items-center gap-1.5 text-sm text-slate-600 dark:text-slate-300 cursor-pointer h-9 select-none">
                            <input type="checkbox" id="topCitedFilter" name="top_cited" class="rounded border-slate-300 text-sky-500 focus:ring-sky-500">
                            Sitasi Terbanyak
                        </label>

                        <div class="w-px h-7 bg-slate-200 dark:bg-[#2a2a2a] hidden sm:block mx-1"></div>

                        <!-- Publication Type -->
                        <select id="pubTypeFilter" name="pub_type" class="h-9 rounded-xl border border-slate-200/70 bg-white/90 px-3 text-sm text-slate-700 focus:border-sky-400 focus:ring-sky-400 transition dark:bg-[#111111] dark:border-[#2a2a2a] dark:text-[#ededed]">
                            <option value="">Semua Tipe</option>
                            <option value="JournalArticle">Jurnal</option>
                            <option value="Conference">Konferensi</option>
                            <option value="Review">Review</option>
                            <option value="Book">Buku</option>
                        </select>

                        <div class="w-px h-7 bg-slate-200 dark:bg-[#2a2a2a] hidden sm:block mx-1"></div>

                        <!-- Language Filter -->
                        <select id="languageFilter" name="language" class="h-9 rounded-xl border border-slate-200/70 bg-white/90 px-3 text-sm text-slate-700 focus:border-sky-400 focus:ring-sky-400 transition dark:bg-[#111111] dark:border-[#2a2a2a] dark:text-[#ededed]">
                            <option value="">Semua Bahasa</option>
                            <option value="en">Bahasa Inggris</option>
                            <option value="id">Bahasa Indonesia</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Search Results -->
            <div id="searchResults" class="hidden space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        Hasil Pencarian
                        <span id="resultsCount" class="ml-2 text-sm font-normal text-slate-500 dark:text-slate-400"></span>
                    </h3>
                    <div class="flex items-center gap-2">
                        <select id="sortResults" class="text-sm border-slate-200 rounded-lg focus:border-sky-500 focus:ring-sky-500">
                            <option value="relevance">Relevansi</option>
                            <option value="year_desc">Tahun (Terbaru)</option>
                            <option value="year_asc">Tahun (Terlama)</option>
                            <option value="citations">Jumlah Sitasi</option>
                        </select>
                    </div>
                </div>
                
                <div id="resultsList" class="grid gap-4">
                    <!-- Results will be inserted here -->
                </div>

                <!-- Pagination -->
                <div id="pagination" class="flex items-center justify-center gap-2 pt-4">
                    <!-- Pagination buttons will be inserted here -->
                </div>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="hidden py-12">
                <div class="flex flex-col items-center justify-center gap-4">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-sky-500"></div>
                    <p class="text-slate-600 dark:text-slate-300">Mencari paper...</p>
                </div>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="py-12 text-center feature-surface">
                <div class="mx-auto h-24 w-24 rounded-full bg-sky-100/70 dark:bg-white/10 flex items-center justify-center mb-4">
                    <svg class="h-12 w-12 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Mulai Pencarian Paper</h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                    Masukkan kata kunci untuk menemukan paper akademik dari berbagai sumber terpercaya.
                </p>
            </div>

            <!-- Recent Searches & Saved References -->
            @if(false)
            <div class="feature-surface bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Referensi Tersimpan</h3>
                <div class="grid gap-3">
                    @foreach($recentSearches as $reference)
                    <div class="feature-muted flex items-start justify-between p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-slate-900 dark:text-white truncate">{{ $reference->title }}</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                @if($reference->authors)
                                    {{ is_string($reference->authors) ? implode(', ', json_decode($reference->authors, true) ?? []) : implode(', ', $reference->authors) }}
                                @endif
                                @if($reference->year)
                                    · {{ $reference->year }}
                                @endif
                            </p>
                            @if($reference->venue)
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $reference->venue }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            @if($reference->citation_count)
                                <span class="text-xs text-slate-500 dark:text-slate-400" title="Citation Count">
                                    {{ $reference->citation_count }} sitasi
                                </span>
                            @endif
                            <a href="{{ $reference->url ?? '#' }}" target="_blank" class="text-sky-500 hover:text-sky-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- Save Reference Modal -->
    <div id="saveModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeSaveModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="feature-surface inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white dark:bg-transparent px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-sky-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white" id="modal-title">Simpan Referensi</h3>
                            <div class="mt-4">
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Pilih skripsi untuk menyimpan referensi ini:</p>
                                <select id="thesisSelect" class="block w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500">
                                    <option value="">-- Pilih Skripsi --</option>
                                    @foreach([] as $thesis)
                                        <option value="">{{ '' }}</option>
                                    @endforeach
                                </select>
                                <div id="saveError" class="mt-2 text-sm text-red-600 hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-950/60 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="confirmSaveReference()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-sky-500 text-base font-medium text-white hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="closeSaveModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-700 shadow-sm px-4 py-2 bg-white dark:bg-slate-900 text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentPaper = null;
        let currentResults = [];

        document.getElementById('searchForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const query = document.getElementById('query').value;
            const limit = document.getElementById('limit').value;
            const button = document.getElementById('searchButton');
            const buttonText = document.getElementById('searchButtonText');
            
            // Show loading state
            button.disabled = true;
            buttonText.textContent = 'Mencari...';
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('searchResults').classList.add('hidden');
            
            try {
                const yearStart = document.querySelector('input[name="year_filter"]:checked')?.value || null;
                const openAccess = document.getElementById('openAccessFilter').checked;
                const pubType = document.getElementById('pubTypeFilter').value;
                const topCited = document.getElementById('topCitedFilter').checked;
                const language = document.getElementById('languageFilter').value;

                const response = await fetch('{{ route("paper.search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        query,
                        limit,
                        year_start: yearStart,
                        open_access: openAccess,
                        pub_type: pubType,
                        top_cited: topCited,
                        language: language || null
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    currentResults = data.data.data || [];
                    displayResults(currentResults, data.data.total || 0, data.source || 'semantic_scholar');
                } else {
                    showError(data.message || 'Terjadi kesalahan saat mencari.');
                }
            } catch (error) {
                showError('Terjadi kesalahan. Silakan coba lagi.');
                console.error(error);
            } finally {
                button.disabled = false;
                buttonText.textContent = 'Cari Paper';
                document.getElementById('loadingState').classList.add('hidden');
            }
        });

        function displayResults(papers, total, source = 'semantic_scholar') {
            const container = document.getElementById('resultsList');
            const resultsSection = document.getElementById('searchResults');
            const countElement = document.getElementById('resultsCount');

            container.innerHTML = '';
            const aiBadge = source === 'ai_fallback' ? ' <span class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-2 py-0.5 text-xs font-medium text-sky-600 dark:bg-sky-950/50 dark:text-sky-200 ml-2"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>AI</span>' : '';
            countElement.innerHTML = `(${total} ditemukan)${aiBadge}`;
            
            if (papers.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <p class="text-slate-500">Tidak ada hasil yang ditemukan.</p>
                    </div>
                `;
            } else {
                papers.forEach(paper => {
                    const authors = paper.authors ? paper.authors.map(a => a.name).join(', ') : 'Unknown';
                    const year = paper.year || 'N/A';
                    const venue = paper.venue || '';
                    const citations = paper.citationCount || 0;
                    const hasPdf = paper.openAccessPdf ? true : false;
                    
                    const card = document.createElement('div');
                    card.className = 'feature-surface p-5 hover:-translate-y-1 transition-transform';
                    card.innerHTML = `
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-slate-900 text-lg leading-tight mb-2">${paper.title}</h4>
                                <p class="text-sm text-slate-600 mb-1">
                                    <span class="font-medium">${authors}</span>
                                    <span class="text-slate-400"> · ${year}</span>
                                </p>
                                ${venue ? `<p class="text-sm text-slate-500 mb-2">${venue}</p>` : ''}
                                ${paper.abstract ? `<p class="text-sm text-slate-500 line-clamp-2 mb-3">${paper.abstract}</p>` : ''}
                                <div class="flex flex-wrap items-center gap-3 text-xs">
                                    <span class="inline-flex items-center gap-1 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        ${citations} sitasi
                                    </span>
                                    ${hasPdf ? `
                                        <a href="${paper.openAccessPdf.url}" target="_blank" class="inline-flex items-center gap-1 text-sky-500 hover:text-sky-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            PDF
                                        </a>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <button onclick="toggleSavePaper('${paper.paperId}', '${escapeHtml(paper.title)}', '${escapeHtml(authors)}', ${year}, '${escapeHtml(paper.abstract || '')}', '${escapeHtml(venue)}', ${citations})" 
                                    class="save-btn inline-flex items-center gap-1.5 rounded-lg bg-sky-50 px-3 py-2 text-sm font-medium text-sky-600 hover:bg-sky-100 transition-colors" data-paper-id="${paper.paperId}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    `;
                    container.appendChild(card);
                });
            }
            
            resultsSection.classList.remove('hidden');
            checkSavedStatus();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showError(message) {
            const container = document.getElementById('resultsList');
            container.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                    <p class="text-red-600">${message}</p>
                </div>
            `;
            document.getElementById('searchResults').classList.remove('hidden');
        }

        async function toggleSavePaper(paperId, title, authors, year, abstract, venue, citations) {
            try {
                const response = await fetch('{{ route("paper.toggle-save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        external_id: paperId,
                        title: title,
                        authors: authors,
                        year: year !== 'N/A' ? parseInt(year) : null,
                        abstract: abstract || null,
                        venue: venue || null,
                        citation_count: citations
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    updateSaveButton(paperId, data.saved);
                }
            } catch (error) {
                console.error('Failed to toggle save:', error);
            }
        }

        function updateSaveButton(paperId, saved) {
            const btn = document.querySelector(`.save-btn[data-paper-id="${paperId}"]`);
            if (!btn) return;
            if (saved) {
                btn.classList.add('bg-sky-500', 'text-white');
                btn.classList.remove('bg-sky-50', 'text-sky-600', 'hover:bg-sky-100');
                btn.querySelector('svg path').setAttribute('d', 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z');
                btn.querySelector('svg').setAttribute('fill', 'currentColor');
                btn.querySelector('svg').setAttribute('stroke', 'none');
                btn.childNodes[btn.childNodes.length - 1].textContent = ' Tersimpan';
            } else {
                btn.classList.remove('bg-sky-500', 'text-white');
                btn.classList.add('bg-sky-50', 'text-sky-600', 'hover:bg-sky-100');
                btn.querySelector('svg').setAttribute('fill', 'none');
                btn.querySelector('svg').setAttribute('stroke', 'currentColor');
                btn.childNodes[btn.childNodes.length - 1].textContent = ' Simpan';
            }
        }

        async function checkSavedStatus() {
            const ids = currentResults.map(p => p.paperId);
            if (ids.length === 0) return;
            try {
                const response = await fetch('{{ route("paper.check-saved") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids })
                });
                const data = await response.json();
                if (data.success && data.saved_ids) {
                    data.saved_ids.forEach(id => updateSaveButton(id, true));
                }
            } catch (e) {
                console.error('Failed to check saved status:', e);
            }
        }

        // Sort functionality
        document.getElementById('sortResults')?.addEventListener('change', function() {
            const sortBy = this.value;
            let sorted = [...currentResults];
            
            switch(sortBy) {
                case 'year_desc':
                    sorted.sort((a, b) => (b.year || 0) - (a.year || 0));
                    break;
                case 'year_asc':
                    sorted.sort((a, b) => (a.year || 0) - (b.year || 0));
                    break;
                case 'citations':
                    sorted.sort((a, b) => (b.citationCount || 0) - (a.citationCount || 0));
                    break;
            }
            
            displayResults(sorted, sorted.length);
        });
    </script>
    @endpush
</x-app-layout>
