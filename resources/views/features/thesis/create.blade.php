<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900">
                    Buat Skripsi Baru
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Mulai perjalanan akademik Anda dengan menentukan fokus penelitian.
                </p>
            </div>
            <a href="{{ route('thesis.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <form action="{{ route('thesis.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-2">
                            Judul Skripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="title" 
                            name="title" 
                            rows="3"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-sky-500"
                            placeholder="Contoh: Analisis Pengaruh Digital Marketing terhadap Peningkatan Penjualan UMKM di Indonesia"
                            required
                        >{{ old('title') }}</textarea>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="field" class="block text-sm font-medium text-slate-700 mb-2">
                                Bidang Studi <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="field" 
                                name="field"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-sky-500"
                                placeholder="Contoh: Manajemen Bisnis"
                                value="{{ old('field') }}"
                                required
                            >
                            @error('field')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="topic" class="block text-sm font-medium text-slate-700 mb-2">
                                Topik Spesifik <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="topic" 
                                name="topic"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-sky-500"
                                placeholder="Contoh: Digital Marketing UMKM"
                                value="{{ old('topic') }}"
                                required
                            >
                            @error('topic')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="advisor_name" class="block text-sm font-medium text-slate-700 mb-2">
                            Nama Dosen Pembimbing
                        </label>
                        <input 
                            type="text" 
                            id="advisor_name" 
                            name="advisor_name"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-sky-500"
                            placeholder="Contoh: Dr. Budi Santoso, M.M."
                            value="{{ old('advisor_name') }}"
                        >
                        @error('advisor_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                            Deskripsi/Abstrak Awal
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="5"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-sky-500"
                            placeholder="Jelaskan latar belakang, rumusan masalah, dan tujuan penelitian Anda..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-slate-700 mb-2">
                                Tanggal Mulai
                            </label>
                            <input 
                                type="date" 
                                id="start_date" 
                                name="start_date"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-sky-500"
                                value="{{ old('start_date', now()->format('Y-m-d')) }}"
                            >
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="target_completion_date" class="block text-sm font-medium text-slate-700 mb-2">
                                Target Selesai
                            </label>
                            <input 
                                type="date" 
                                id="target_completion_date" 
                                name="target_completion_date"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-sky-500"
                                value="{{ old('target_completion_date') }}"
                            >
                            @error('target_completion_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('thesis.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-sky-500 text-white font-semibold shadow-lg shadow-sky-200 hover:bg-sky-600 transition-colors">
                            Buat Skripsi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
