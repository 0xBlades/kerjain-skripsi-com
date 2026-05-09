<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900 dark:text-white">Detail Skripsi</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $thesis->title }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('thesis.edit', $thesis) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:border-sky-300 hover:text-sky-600 transition dark:border-[#2a2a2a] dark:bg-[#1a1a1a] dark:text-[#ededed]">Edit</a>
                <a href="{{ route('thesis.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:border-sky-300 hover:text-sky-600 transition dark:border-[#2a2a2a] dark:bg-[#1a1a1a] dark:text-[#ededed]">Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Thesis Info -->
            <div class="feature-surface p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white leading-tight">{{ $thesis->title }}</h1>
                        <div class="flex flex-wrap items-center gap-3 mt-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                {{ $thesis->status === 'draft' ? 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-[#b5b5b5]' : 
                                   ($thesis->status === 'in_progress' ? 'bg-sky-100 text-sky-600 dark:bg-white/10 dark:text-white' : 
                                   ($thesis->status === 'under_review' ? 'bg-yellow-100 text-yellow-600 dark:bg-white/10 dark:text-yellow-200' : 
                                   'bg-green-100 text-green-600 dark:bg-white/10 dark:text-green-200')) }}">
                                {{ ucfirst(str_replace('_', ' ', $thesis->status)) }}
                            </span>
                            <span class="text-sm text-slate-500 dark:text-[#888]">{{ $thesis->topic }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400 dark:text-[#666] mb-1">Progress</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $thesis->progress_percentage ?? $thesis->progress ?? 0 }}%</p>
                    </div>
                </div>

                <div class="progress-track h-2 rounded-full overflow-hidden mb-6">
                    <div class="h-full bg-gradient-to-r from-sky-400 to-cyan-400 rounded-full transition-all" style="width: {{ $thesis->progress_percentage ?? $thesis->progress ?? 0 }}%"></div>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                    @if($thesis->supervisor_name)
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 dark:text-[#666]">Pembimbing</p>
                        <p class="mt-1 font-medium text-slate-900 dark:text-[#ededed]">{{ $thesis->supervisor_name }}</p>
                    </div>
                    @endif
                    @if($thesis->target_completion_date)
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 dark:text-[#666]">Target Selesai</p>
                        <p class="mt-1 font-medium text-slate-900 dark:text-[#ededed]">{{ \Carbon\Carbon::parse($thesis->target_completion_date)->format('d M Y') }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 dark:text-[#666]">Progress</p>
                        <p class="mt-1 font-medium text-slate-900 dark:text-[#ededed]">{{ $thesis->progress_percentage ?? $thesis->progress ?? 0 }}%</p>
                    </div>
                </div>
            </div>

            <!-- Chapter Progress -->
            <div class="feature-surface p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Progress per Bab</h3>
                    <span class="text-xs text-slate-400 dark:text-[#666]">Klik bar untuk edit</span>
                </div>
                <div class="space-y-4" id="chapterProgressList">
                    @foreach($chapterProgress as $num => $chapter)
                    <div class="flex items-center gap-4 group cursor-pointer" onclick="editChapterProgress({{ $num }}, '{{ $chapter['name'] }}', {{ $chapter['progress'] }})">
                        <div class="h-10 w-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-sm font-semibold text-slate-500 dark:text-[#888] shrink-0">{{ $num }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-medium text-slate-700 dark:text-[#d1d1d1] truncate">{{ $chapter['name'] }}</span>
                                <span class="text-xs text-slate-400 dark:text-[#666] ml-2 shrink-0" id="progress-text-{{ $num }}">{{ $chapter['progress'] }}%</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-slate-100 dark:bg-[#2a2a2a] overflow-hidden relative">
                                <div id="progress-bar-{{ $num }}" class="h-full rounded-full transition-all {{ $chapter['status'] === 'completed' ? 'bg-green-400' : 'bg-sky-400' }}" style="width: {{ $chapter['progress'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Chapter Edit Modal -->
            <div id="chapterModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
                <div class="feature-surface p-6 w-full max-w-md mx-4">
                    <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4" id="modalTitle">Edit Progress Bab</h4>
                    <div class="mb-6">
                        <label class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-3">Progress (%)</label>
                        <input type="range" id="progressRange" min="0" max="100" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer dark:bg-[#2a2a2a] accent-sky-500">
                        <div class="flex justify-between mt-2">
                            <span class="text-xs text-slate-400">0%</span>
                            <span id="rangeValue" class="text-sm font-semibold text-sky-500">0%</span>
                            <span class="text-xs text-slate-400">100%</span>
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button onclick="closeModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 text-sm hover:bg-slate-50 transition dark:border-[#2a2a2a] dark:text-[#ededed] dark:hover:bg-[#1a1a1a]">Batal</button>
                        <button onclick="saveChapterProgress()" class="px-4 py-2 rounded-full bg-sky-500 text-white text-sm font-semibold hover:bg-sky-600 transition">Simpan</button>
                    </div>
                </div>
            </div>

            <script>
                let currentChapter = null;
                const csrfToken = '{{ csrf_token() }}';

                function editChapterProgress(num, name, currentProgress) {
                    currentChapter = num;
                    document.getElementById('modalTitle').textContent = 'Edit: ' + name;
                    const range = document.getElementById('progressRange');
                    range.value = currentProgress;
                    document.getElementById('rangeValue').textContent = currentProgress + '%';
                    document.getElementById('chapterModal').classList.remove('hidden');
                    document.getElementById('chapterModal').classList.add('flex');
                }

                function closeModal() {
                    document.getElementById('chapterModal').classList.add('hidden');
                    document.getElementById('chapterModal').classList.remove('flex');
                    currentChapter = null;
                }

                document.getElementById('progressRange').addEventListener('input', function() {
                    document.getElementById('rangeValue').textContent = this.value + '%';
                });

                async function saveChapterProgress() {
                    if (!currentChapter) return;
                    const progress = parseInt(document.getElementById('progressRange').value);
                    const status = progress === 100 ? 'completed' : progress === 0 ? 'not_started' : 'in_progress';

                    try {
                        const response = await fetch('{{ route("thesis.chapter-progress", $thesis) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                chapter: currentChapter,
                                progress: progress,
                                status: status
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            // Update UI
                            document.getElementById('progress-text-' + currentChapter).textContent = progress + '%';
                            const bar = document.getElementById('progress-bar-' + currentChapter);
                            bar.style.width = progress + '%';
                            bar.className = 'h-full rounded-full transition-all ' + (progress === 100 ? 'bg-green-400' : 'bg-sky-400');
                            // Update overall progress display
                            document.querySelector('.text-3xl.font-bold').textContent = data.overall_progress + '%';
                            document.querySelector('.progress-track > div').style.width = data.overall_progress + '%';
                            closeModal();
                        }
                    } catch (e) {
                        console.error('Failed to update progress:', e);
                        alert('Gagal mengupdate progress. Silakan coba lagi.');
                    }
                }

                // Close modal on outside click
                document.getElementById('chapterModal').addEventListener('click', function(e) {
                    if (e.target === this) closeModal();
                });
            </script>

            <!-- Quick Actions -->
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('ai-writing.index') }}" class="feature-surface p-4 hover:-translate-y-1 transition-transform text-center w-full sm:w-[calc(50%-0.5rem)] lg:w-[calc(33.333%-0.75rem)] max-w-xs">
                    <div class="h-10 w-10 rounded-xl bg-sky-100/70 dark:bg-white/10 flex items-center justify-center mx-auto mb-3">
                        <svg class="h-5 w-5 text-sky-500 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-[#ededed]">AI Writing</p>
                    <p class="text-xs text-slate-400 dark:text-[#666] mt-1">Generate konten bab</p>
                </a>
                <a href="{{ route('paper.index', ['thesis_id' => $thesis->id]) }}" class="feature-surface p-4 hover:-translate-y-1 transition-transform text-center w-full sm:w-[calc(50%-0.5rem)] lg:w-[calc(33.333%-0.75rem)] max-w-xs">
                    <div class="h-10 w-10 rounded-xl bg-sky-100/70 dark:bg-white/10 flex items-center justify-center mx-auto mb-3">
                        <svg class="h-5 w-5 text-sky-500 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-[#ededed]">Cari Paper</p>
                    <p class="text-xs text-slate-400 dark:text-[#666] mt-1">Temukan referensi</p>
                </a>
                <a href="{{ route('thesis.edit', $thesis) }}" class="feature-surface p-4 hover:-translate-y-1 transition-transform text-center w-full sm:w-[calc(50%-0.5rem)] lg:w-[calc(33.333%-0.75rem)] max-w-xs">
                    <div class="h-10 w-10 rounded-xl bg-sky-100/70 dark:bg-white/10 flex items-center justify-center mx-auto mb-3">
                        <svg class="h-5 w-5 text-sky-500 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-[#ededed]">Edit Skripsi</p>
                    <p class="text-xs text-slate-400 dark:text-[#666] mt-1">Ubah detail & status</p>
                </a>
            </div>

            <!-- Recent Activities -->
            @if(count($recentActivities) > 0)
            <div class="feature-surface p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-3">
                    @foreach($recentActivities as $activity)
                    <div class="feature-muted flex items-center gap-3 p-3">
                        <div class="h-8 w-8 rounded-lg bg-sky-100/70 dark:bg-white/10 flex items-center justify-center shrink-0">
                            @if($activity['icon'] === 'document')
                            <svg class="h-4 w-4 text-sky-500 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @else
                            <svg class="h-4 w-4 text-sky-500 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-slate-700 dark:text-[#d1d1d1] truncate">{{ $activity['description'] }}</p>
                            <p class="text-xs text-slate-400 dark:text-[#666]">{{ $activity['date']->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
