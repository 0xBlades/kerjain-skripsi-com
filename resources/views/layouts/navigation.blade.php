<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-xl border-b border-sky-100 sticky top-0 z-30 dark:bg-[#111111] dark:border-[#2a2a2a]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-sky-500 dark:text-sky-300" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('paper.index')" :active="request()->routeIs('paper.*')">
                        {{ __('Cari Paper') }}
                    </x-nav-link>
                    <x-nav-link :href="route('ai-writing.index')" :active="request()->routeIs('ai-writing.*')">
                        {{ __('AI Writing') }}
                    </x-nav-link>
                    <x-nav-link :href="route('thesis.index')" :active="request()->routeIs('thesis.*')">
                        {{ __('Skripsi') }}
                    </x-nav-link>
                    <x-nav-link :href="route('paper.saved')" :active="request()->routeIs('paper.saved')">
                        {{ __('Paper Tersimpan') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <button
                    type="button"
                    onclick="toggleTheme()"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-sky-100 bg-white text-slate-600 shadow-sm transition hover:border-sky-300 hover:text-sky-600 dark:border-[#2a2a2a] dark:bg-[#1a1a1a] dark:text-[#ededed] dark:hover:border-[#444444] dark:hover:text-white"
                    aria-label="Toggle theme"
                >
                    <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-10h-1M4.34 12h-1m14.14-6.36l-.7.7M6.22 17.78l-.7.7m12.02 0l-.7-.7M6.22 6.22l-.7-.7M12 7a5 5 0 100 10 5 5 0 000-10z" />
                    </svg>
                </button>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-sky-100 text-sm leading-4 font-medium rounded-full text-slate-700 bg-white hover:border-sky-200 hover:text-sky-700 focus:outline-none transition ease-in-out duration-150 shadow-sm dark:border-[#2a2a2a] dark:bg-[#1a1a1a] dark:text-[#ededed] dark:hover:border-[#444444] dark:hover:text-white">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-500 hover:text-sky-700 hover:bg-sky-50 focus:outline-none focus:bg-sky-50 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-sky-100 dark:bg-[#111111] dark:border-[#2a2a2a]">
        <div class="pt-2 pb-3 space-y-1">
            <button
                type="button"
                onclick="toggleTheme()"
                class="mx-3 mb-2 inline-flex items-center gap-2 rounded-xl border border-sky-100 bg-white px-3 py-2 text-sm font-medium text-slate-600 dark:border-[#2a2a2a] dark:bg-[#1a1a1a] dark:text-[#ededed]"
            >
                Toggle Light/Dark
            </button>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('paper.index')" :active="request()->routeIs('paper.*')">
                {{ __('Cari Paper') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('ai-writing.index')" :active="request()->routeIs('ai-writing.*')">
                {{ __('AI Writing') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('thesis.index')" :active="request()->routeIs('thesis.*')">
                {{ __('Skripsi') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('paper.saved')" :active="request()->routeIs('paper.saved')">
                {{ __('Paper Tersimpan') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-sky-100 dark:border-[#2a2a2a]">
            <div class="px-4">
                <div class="font-medium text-base text-slate-900 dark:text-[#ededed]">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500 dark:text-[#888888]">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
    <script>
        function toggleTheme() {
            const root = document.documentElement;
            const isDark = root.classList.toggle('dark');
            localStorage.theme = isDark ? 'dark' : 'light';
        }
    </script>
</nav>
