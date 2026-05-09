<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900 dark:text-white">Paper Tersimpan</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Daftar paper yang sudah kamu simpan sebagai referensi.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if($papers->count() === 0)
                <div class="feature-surface p-12 text-center">
                    <div class="mx-auto h-24 w-24 rounded-full bg-sky-100/70 dark:bg-white/10 flex items-center justify-center mb-4">
                        <svg class="h-12 w-12 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Belum Ada Paper Tersimpan</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-6">Simpan paper dari hasil pencarian untuk dikumpulkan di sini sebagai daftar referensi.</p>
                    <a href="{{ route('paper.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-200 hover:bg-sky-600">Cari Paper</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($papers as $paper)
                    @php
                        $paperUrl = $paper->url ?: ($paper->external_id ? "https://www.semanticscholar.org/paper/{$paper->external_id}" : null);
                    @endphp
                    <div class="feature-surface p-5 flex gap-4 hover:border-sky-500/30 transition-colors group">
                        <div class="flex-1 min-w-0">
                            @if($paperUrl)
                            <a href="{{ $paperUrl }}" target="_blank" class="block group">
                                <h3 class="text-base font-semibold text-slate-900 dark:text-white leading-snug mb-1 group-hover:text-sky-500 transition-colors">{{ $paper->title }}</h3>
                            </a>
                            @else
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white leading-snug mb-1">{{ $paper->title }}</h3>
                            @endif
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">{{ $paper->authors ?? 'Unknown authors' }}</p>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400 dark:text-[#666]">
                                @if($paper->published_at)
                                <span class="inline-flex items-center gap-1"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ \Carbon\Carbon::parse($paper->published_at)->format('Y') }}</span>
                                @endif
                                @if($paper->publisher)
                                <span>{{ $paper->publisher }}</span>
                                @endif
                                @if(isset($paper->metadata['citation_count']))
                                <span class="inline-flex items-center gap-1"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg> {{ $paper->metadata['citation_count'] }} sitasi</span>
                                @endif
                            </div>
                            @if($paper->abstract)
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-3 line-clamp-2">{{ $paper->abstract }}</p>
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            <button onclick="removePaper('{{ $paper->external_id }}')" class="p-2 rounded-lg text-sky-500 hover:bg-sky-50 dark:hover:bg-sky-500/10 transition" title="Hapus dari tersimpan">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            </button>
                            @if($paperUrl)
                            <a href="{{ $paperUrl }}" target="_blank" class="p-2 rounded-lg text-slate-400 hover:text-sky-500 hover:bg-sky-50 dark:hover:bg-sky-500/10 transition" title="Buka paper">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $papers->links() }}
                </div>
            @endif

        </div>
    </div>

    <script>
        async function removePaper(id) {
            if (!confirm('Hapus paper ini dari daftar tersimpan?')) return;
            try {
                const response = await fetch('{{ route("paper.toggle-save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ external_id: id, title: 'x' })
                });
                if (response.ok) {
                    location.reload();
                }
            } catch (e) {
                alert('Gagal menghapus paper.');
            }
        }
    </script>
</x-app-layout>
