<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 dark:text-slate-100 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="relative isolate overflow-hidden py-12 sm:py-16">
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-slate-100 via-indigo-100 to-blue-100 dark:from-[#030712] dark:via-[#0b1220] dark:to-[#04070f]"></div>
        <div class="absolute -top-32 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-sky-400/30 blur-3xl dark:bg-sky-500/20"></div>
        <div class="absolute top-20 -right-20 h-64 w-64 rounded-full bg-indigo-400/30 blur-3xl dark:bg-indigo-500/20"></div>
        <div class="absolute bottom-0 left-10 h-56 w-56 rounded-full bg-purple-400/20 blur-3xl dark:bg-purple-500/20"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <!-- Profile Hero Card -->
            <div class="rounded-3xl border border-white/60 bg-white/80 backdrop-blur-xl shadow-2xl dark:border-white/5 dark:bg-slate-900/70">
                <div class="relative overflow-hidden rounded-3xl">
                    <div class="absolute inset-x-0 -top-24 h-48 bg-gradient-to-r from-sky-400 via-indigo-500 to-purple-600 opacity-60 blur-3xl dark:opacity-70"></div>
                    <div class="relative px-6 pb-8 pt-12 sm:px-10 sm:pt-16 sm:pb-10">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-8">
                            <div class="relative flex-shrink-0">
                                <div class="absolute inset-0 rounded-full bg-indigo-500/30 blur-xl"></div>
                                @if ($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="relative w-28 h-28 rounded-full border-4 border-white shadow-xl dark:border-slate-900 object-cover">
                                @else
                                    <div class="relative w-28 h-28 rounded-full border-4 border-white shadow-xl dark:border-slate-900 bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                        <span class="text-3xl font-bold text-indigo-600 dark:text-indigo-300">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <p class="text-sm font-medium uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">{{ __('Auth by') }} {{ $user->google_id ? 'Google' : 'Email' }}</p>
                                <h1 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">{{ $user->name }}</h1>
                                <p class="mt-1 text-base text-slate-500 dark:text-slate-300">{{ $user->email }}</p>
                                <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-3">
                                    @if ($user->google_id)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-1.5 text-xs font-semibold text-slate-600 shadow-sm dark:bg-slate-800/80 dark:text-slate-100">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24">
                                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                            </svg>
                                            Google Account
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-2 rounded-full bg-indigo-500/10 px-4 py-1.5 text-xs font-semibold text-indigo-600 dark:bg-indigo-400/10 dark:text-indigo-200">
                                        <span class="h-2 w-2 rounded-full bg-current"></span>
                                        {{ __('Member since') }} {{ $user->created_at?->format('M Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-center gap-3 sm:items-end">
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-white/90 px-4 py-2 text-sm font-semibold text-indigo-600 shadow-sm transition hover:border-indigo-300 hover:bg-white dark:border-indigo-500/40 dark:bg-slate-800/80 dark:text-indigo-200 dark:hover:border-indigo-400/60">
                                    <span>{{ __('Back to dashboard') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Grid -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-white/50 bg-white/80 p-6 backdrop-blur-xl shadow-xl dark:border-white/5 dark:bg-slate-900/70 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="rounded-3xl border border-white/50 bg-white/80 p-6 backdrop-blur-xl shadow-xl dark:border-white/5 dark:bg-slate-900/70 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-3xl border border-red-200/60 bg-white/80 p-6 backdrop-blur-xl shadow-xl dark:border-red-500/20 dark:bg-red-500/10 sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
