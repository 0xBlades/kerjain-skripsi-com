<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900 dark:text-white">Edit Skripsi</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Perbarui detail skripsi Anda.</p>
            </div>
            <a href="{{ route('thesis.show', $thesis) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:border-sky-300 hover:text-sky-600 transition dark:border-[#2a2a2a] dark:bg-[#1a1a1a] dark:text-[#ededed]">Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="feature-surface p-8">
                <form action="{{ route('thesis.update', $thesis) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-2">Judul Skripsi <span class="text-red-500">*</span></label>
                        <textarea id="title" name="title" rows="3" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 focus:bg-white focus:border-sky-400 focus:ring-sky-400 transition" placeholder="Contoh: Analisis Pengaruh Digital Marketing..." required>{{ old('title', $thesis->title) }}</textarea>
                        @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="field" class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-2">Bidang Studi <span class="text-red-500">*</span></label>
                            <input type="text" id="field" name="field" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-2.5 focus:bg-white focus:border-sky-400 focus:ring-sky-400 transition" placeholder="Contoh: Manajemen Bisnis" value="{{ old('field', $thesis->field) }}" required>
                            @error('field')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="topic" class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-2">Topik Spesifik <span class="text-red-500">*</span></label>
                            <input type="text" id="topic" name="topic" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-2.5 focus:bg-white focus:border-sky-400 focus:ring-sky-400 transition" placeholder="Contoh: Digital Marketing UMKM" value="{{ old('topic', $thesis->topic) }}" required>
                            @error('topic')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="advisor_name" class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-2">Nama Dosen Pembimbing</label>
                        <input type="text" id="advisor_name" name="advisor_name" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-2.5 focus:bg-white focus:border-sky-400 focus:ring-sky-400 transition" placeholder="Contoh: Dr. Budi Santoso, M.M." value="{{ old('advisor_name', $thesis->supervisor_name ?? '') }}">
                        @error('advisor_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-2">Deskripsi/Abstrak Awal</label>
                        <textarea id="description" name="description" rows="5" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 focus:bg-white focus:border-sky-400 focus:ring-sky-400 transition" placeholder="Jelaskan latar belakang, rumusan masalah...">{{ old('description', $thesis->description ?? '') }}</textarea>
                        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-2">Status</label>
                            <select id="status" name="status" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-2.5 focus:border-sky-400 focus:ring-sky-400 transition">
                                <option value="draft" {{ (old('status', $thesis->status) == 'draft') ? 'selected' : '' }}>Draft</option>
                                <option value="in_progress" {{ (old('status', $thesis->status) == 'in_progress') ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                <option value="under_review" {{ (old('status', $thesis->status) == 'under_review') ? 'selected' : '' }}>Dalam Review</option>
                                <option value="completed" {{ (old('status', $thesis->status) == 'completed') ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="progress_percentage" class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-2">Progress (%)</label>
                            <input type="number" id="progress_percentage" name="progress_percentage" min="0" max="100" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-2.5 focus:border-sky-400 focus:ring-sky-400 transition" value="{{ old('progress_percentage', $thesis->progress_percentage ?? $thesis->progress ?? 0) }}">
                            @error('progress_percentage')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="target_completion_date" class="block text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 mb-2">Target Selesai</label>
                        <input type="date" id="target_completion_date" name="target_completion_date" class="block w-full rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-2.5 focus:border-sky-400 focus:ring-sky-400 transition" value="{{ old('target_completion_date', $thesis->target_completion_date ? \Carbon\Carbon::parse($thesis->target_completion_date)->format('Y-m-d') : '') }}">
                        @error('target_completion_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100 dark:border-[#2a2a2a]">
                        <a href="{{ route('thesis.show', $thesis) }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition dark:border-[#2a2a2a] dark:text-[#ededed] dark:hover:bg-[#1a1a1a]">Batal</a>
                        <button type="submit" class="px-6 py-2.5 rounded-full bg-sky-500 text-white font-semibold shadow-lg shadow-sky-200 hover:bg-sky-600 transition-colors">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
