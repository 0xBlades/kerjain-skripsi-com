<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900 dark:text-white">
                    Manajemen Dokumen
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Kelola dan organisir semua dokumen skripsi Anda.
                </p>
            </div>
            <a href="{{ route('documents.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-200 hover:bg-sky-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Upload Dokumen
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Filters -->
            <div class="feature-surface p-5">
                <div class="flex flex-wrap gap-4 items-center">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Filter:</span>
                    </div>
                    <select id="filterThesis" class="rounded-2xl border border-slate-200/70 text-sm px-4 py-2 bg-white/90 focus:border-sky-400 focus:ring-sky-400 transition">
                        <option value="">Semua Skripsi</option>
                        @foreach($theses as $thesis)
                            <option value="{{ $thesis->id }}">{{ $thesis->title }}</option>
                        @endforeach
                    </select>
                    <select id="filterType" class="rounded-2xl border border-slate-200/70 text-sm px-4 py-2 bg-white/90 focus:border-sky-400 focus:ring-sky-400 transition">
                        <option value="">Semua Tipe</option>
                        <option value="chapter">Bab</option>
                        <option value="draft">Draft</option>
                        <option value="final">Final</option>
                        <option value="reference">Referensi</option>
                        <option value="proposal">Proposal</option>
                    </select>
                </div>
            </div>

            @if($documents->count() === 0)
                <!-- Empty State -->
                <div class="feature-surface p-12 text-center">
                    <div class="mx-auto h-24 w-24 rounded-full bg-sky-100/70 dark:bg-white/10 flex items-center justify-center mb-4">
                        <svg class="h-12 w-12 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Belum Ada Dokumen</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-6">
                        Upload dokumen skripsi Anda atau simpan hasil generate dari AI Writing.
                    </p>
                    <a href="{{ route('documents.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-200 hover:bg-sky-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Upload Dokumen Pertama
                    </a>
                </div>
            @else
                <!-- Documents Table -->
                <div class="feature-surface overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                            <thead class="bg-slate-50 dark:bg-slate-950/70">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                        Dokumen
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                        Skripsi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                        Tipe
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                        Ukuran
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-transparent divide-y divide-slate-200 dark:divide-slate-800">
                                @foreach($documents as $document)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-sky-100/70 dark:bg-white/10 flex items-center justify-center">
                                                <svg class="h-5 w-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    @if($document->file_extension === 'pdf')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    @elseif(in_array($document->file_extension, ['doc', 'docx']))
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    @endif
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                                    <a href="{{ route('documents.show', $document) }}" class="hover:text-sky-600">
                                                        {{ $document->title }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $document->file_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-900 dark:text-white">
                                            {{ $document->thesis->title ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $document->type === 'chapter' ? 'bg-purple-100 text-purple-800' : 
                                               ($document->type === 'draft' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($document->type === 'final' ? 'bg-green-100 text-green-800' : 
                                               ($document->type === 'reference' ? 'bg-blue-100 text-blue-800' : 
                                               'bg-slate-100 text-slate-800'))) }}">
                                            {{ ucfirst($document->type) }}
                                            @if($document->chapter_number)
                                                (Bab {{ $document->chapter_number }})
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-300">
                                        {{ number_format($document->file_size / 1024, 2) }} KB
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        {{ $document->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('documents.download', $document) }}" class="text-slate-400 hover:text-sky-600 p-1" title="Download">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('documents.edit', $document) }}" class="text-slate-400 hover:text-sky-600 p-1" title="Edit">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-red-600 p-1" title="Hapus">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($documents->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                        {{ $documents->links() }}
                    </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        // Simple filter functionality (can be enhanced with AJAX)
        document.getElementById('filterThesis')?.addEventListener('change', function() {
            // Implementation for filtering by thesis
            console.log('Filter by thesis:', this.value);
        });

        document.getElementById('filterType')?.addEventListener('change', function() {
            // Implementation for filtering by type
            console.log('Filter by type:', this.value);
        });
    </script>
    @endpush
</x-app-layout>
