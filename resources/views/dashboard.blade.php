<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900 dark:text-white">
                    Dashboard
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Ringkasan progres skripsi dan shortcut fitur utama.
                </p>
            </div>
            <a href="{{ route('thesis.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-200 hover:bg-sky-600">
                <span>+</span>
                Skripsi Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <section class="grid lg:grid-cols-4 gap-5">
                @foreach ($metrics as $metric)
                    <div class="feature-surface p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">{{ $metric['label'] }}</p>
                        <div class="mt-4 flex items-end gap-2">
                            <span class="text-3xl font-semibold text-slate-900 dark:text-white">{{ $metric['value'] }}</span>
                            <span class="pb-1 text-sm text-slate-500 dark:text-slate-400">{{ $metric['suffix'] }}</span>
                        </div>
                        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">{{ $metric['trend'] ?? 'Belum ada aktivitas' }}</p>
                    </div>
                @endforeach
            </section>

            <section class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 feature-surface p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-sky-500">Workspace Aktif</p>
                            <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $thesis->title }}</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $thesis->topic }}</p>
                        </div>
                        <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-600 dark:bg-sky-950/50 dark:text-sky-200">
                            {{ ucfirst(str_replace('_', ' ', $thesis->status)) }}
                        </span>
                    </div>
                    <div class="mt-6">
                        <div class="mb-2 flex justify-between text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Progress</span>
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $thesis->progress ?? $thesis->progress_percentage ?? 0 }}%</span>
                        </div>
                        <div class="h-3 overflow-hidden rounded-full bg-sky-50 dark:bg-slate-800">
                            <div class="h-full rounded-full bg-gradient-to-r from-sky-400 to-cyan-400" style="width: {{ $thesis->progress ?? $thesis->progress_percentage ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mt-6 grid sm:grid-cols-2 gap-3">
                        <a href="{{ route('ai-writing.index') }}" class="rounded-xl border border-sky-100 bg-sky-50 p-4 text-sm font-semibold text-sky-700 hover:bg-sky-100 dark:border-sky-900/50 dark:bg-sky-950/40 dark:text-sky-200">
                            Lanjut AI Writing
                        </a>
                        <a href="{{ route('paper.index') }}" class="rounded-xl border border-sky-100 bg-white p-4 text-sm font-semibold text-slate-700 hover:bg-sky-50 dark:border-sky-900/50 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-sky-950/40">
                            Cari Referensi Baru
                        </a>
                    </div>
                </div>

                <div class="feature-surface p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Deadline Terdekat</h3>
                    <div class="mt-4 space-y-4">
                        @foreach ($deadlines as $deadline)
                            <div class="border-l-2 border-sky-200 pl-4">
                                <p class="font-medium text-slate-900 dark:text-white">{{ $deadline['title'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $deadline['due']->diffForHumans() }} · {{ $deadline['status'] }}</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $deadline['focus'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Shortcut Fitur</h3>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($workspaceShortcuts as $shortcut)
                        <a href="{{ $shortcut['href'] }}" class="feature-surface p-5 hover:-translate-y-1 transition">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-50 text-xl dark:bg-sky-950/50">
                                {{ $shortcut['icon'] }}
                            </div>
                            <h4 class="mt-4 font-semibold text-slate-900 dark:text-white">{{ $shortcut['title'] }}</h4>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $shortcut['description'] }}</p>
                        </a>
                    @endforeach
                </div>
            </section>

            <section class="grid lg:grid-cols-3 gap-6">
                <div class="feature-surface p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Interaksi AI Terbaru</h3>
                    <div class="mt-4 space-y-3">
                        @forelse ($recentInteractions as $interaction)
                            <div class="rounded-xl bg-sky-50 p-3 dark:bg-sky-950/40">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $interaction->feature)) }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $interaction->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada interaksi AI.</p>
                        @endforelse
                    </div>
                </div>

                <div class="feature-surface p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Dokumen Terbaru</h3>
                    <div class="mt-4 space-y-3">
                        @forelse ($documents as $document)
                            <div class="rounded-xl bg-sky-50 p-3 dark:bg-sky-950/40">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $document->title }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $document->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada dokumen.</p>
                        @endforelse
                    </div>
                </div>

                <div class="feature-surface p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Fokus Riset</h3>
                    <div class="mt-4 space-y-3">
                        @foreach ($researchFocus as $focus)
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $focus['title'] }}</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $focus['insight'] }}</p>
                                <p class="mt-1 text-xs text-sky-600">{{ $focus['next'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
