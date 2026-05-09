<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900 dark:text-white">
                    Manajemen Skripsi
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Kelola semua skripsi dan tugas akhir Anda dalam satu tempat.
                </p>
            </div>
            <a href="{{ route('thesis.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-200 hover:bg-sky-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Skripsi Baru
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

            @if($theses->count() === 0)
                <!-- Empty State -->
                <div class="feature-surface p-12 text-center">
                    <div class="mx-auto h-24 w-24 rounded-full bg-sky-100/70 dark:bg-white/10 flex items-center justify-center mb-4">
                        <svg class="h-12 w-12 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Belum Ada Skripsi</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-6">
                        Mulai perjalanan akademik Anda dengan membuat skripsi pertama. Gunakan fitur AI kami untuk membantu penulisan.
                    </p>
                    <a href="{{ route('thesis.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-200 hover:bg-sky-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Skripsi Pertama
                    </a>
                </div>
            @else
                <!-- Thesis Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($theses as $thesis)
                    <div class="feature-surface p-6 hover:-translate-y-1 transition-transform">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $thesis->status === 'draft' ? 'bg-slate-100 text-slate-600' : 
                                       ($thesis->status === 'in_progress' ? 'bg-sky-100 text-sky-600' : 
                                       ($thesis->status === 'under_review' ? 'bg-yellow-100 text-yellow-600' : 
                                       'bg-green-100 text-green-600')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $thesis->status)) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('thesis.edit', $thesis) }}" class="text-slate-400 hover:text-sky-500 p-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('thesis.destroy', $thesis) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus skripsi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-500 p-1">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <a href="{{ route('thesis.show', $thesis) }}" class="block group">
                            <h3 class="font-semibold text-slate-900 dark:text-white text-lg leading-tight mb-2 group-hover:text-sky-600 transition-colors">
                                {{ $thesis->title }}
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                                {{ $thesis->field }} · {{ $thesis->topic }}
                            </p>
                        </a>

                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-slate-600 dark:text-slate-300">Progress</span>
                                    <span class="font-medium text-slate-900 dark:text-white">{{ $thesis->progress_percentage ?? $thesis->progress ?? 0 }}%</span>
                                </div>
                                <div class="progress-track h-2 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-sky-400 to-cyan-400 rounded-full transition-all" style="width: {{ $thesis->progress_percentage ?? $thesis->progress ?? 0 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 pt-3 border-t border-slate-100 dark:border-[#2a2a2a]">
                                <span>{{ \Carbon\Carbon::parse($thesis->created_at)->format('d M Y') }}</span>
                                <span>{{ $thesis->progress_percentage ?? $thesis->progress ?? 0 }}% selesai</span>
                            </div>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('thesis.show', $thesis) }}" class="flex-1 text-center py-2 rounded-lg bg-sky-50 text-sky-600 text-sm font-medium hover:bg-sky-100 transition-colors dark:bg-white/10 dark:text-white">
                                Detail
                            </a>
                            <a href="{{ route('paper.index', ['thesis_id' => $thesis->id]) }}" class="flex-1 text-center py-2 rounded-lg bg-slate-50 text-slate-600 text-sm font-medium hover:bg-slate-100 transition-colors dark:bg-[#1a1a1a] dark:text-[#ededed] dark:hover:bg-[#2a2a2a]">
                                Cari Paper
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
