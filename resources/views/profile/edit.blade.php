<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-white dark:bg-[#0a0a0a]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <!-- Top nav breadcrumb -->
            <div class="mb-10 flex items-center gap-2 text-sm text-neutral-400 dark:text-neutral-500">
                <a href="{{ route('dashboard') }}" class="hover:text-neutral-700 dark:hover:text-neutral-200 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-neutral-700 dark:text-neutral-200 font-medium">Profile Settings</span>
            </div>

            <!-- Profile Header -->
            <div class="mb-10 flex flex-col sm:flex-row sm:items-center gap-6">
                <div class="relative flex-shrink-0">
                    @if ($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-neutral-200 dark:border-neutral-800">
                    @else
                        <div class="w-20 h-20 rounded-full bg-neutral-200 dark:bg-neutral-800 flex items-center justify-center border-2 border-neutral-200 dark:border-neutral-700">
                            <span class="text-2xl font-semibold text-neutral-600 dark:text-neutral-200">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <span class="absolute bottom-0 right-0 h-4 w-4 rounded-full bg-green-400 ring-2 ring-white dark:ring-[#0a0a0a]"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white truncate">{{ $user->name }}</h1>
                    <p class="mt-0.5 text-sm text-neutral-500 dark:text-neutral-400">{{ $user->email }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @if ($user->google_id)
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-neutral-200 bg-neutral-50 px-2.5 py-0.5 text-xs font-medium text-neutral-600 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                <svg class="h-3 w-3" viewBox="0 0 24 24">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                Google
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-neutral-200 bg-neutral-50 px-2.5 py-0.5 text-xs font-medium text-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-400">
                            Joined {{ $user->created_at?->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-neutral-200 dark:border-neutral-800"></div>

            <!-- Sections -->
            <div class="divide-y divide-neutral-200 dark:divide-neutral-800">

                <!-- Personal Info Section -->
                <div class="grid grid-cols-1 gap-x-16 gap-y-6 py-10 md:grid-cols-3">
                    <div>
                        <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Personal Information</h2>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Update your name and email address.</p>
                    </div>
                    <div class="md:col-span-2">
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                            @csrf
                            @method('patch')

                            <div>
                                <label for="name" class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1.5">Name</label>
                                <input id="name" name="name" type="text"
                                    value="{{ old('name', $user->name) }}"
                                    required autocomplete="name"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 transition focus:border-neutral-400 focus:bg-white focus:outline-none focus:ring-0 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 dark:focus:border-neutral-500 dark:focus:bg-neutral-900">
                                @error('name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1.5">Email</label>
                                <input id="email" name="email" type="email"
                                    value="{{ old('email', $user->email) }}"
                                    required autocomplete="username"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 transition focus:border-neutral-400 focus:bg-white focus:outline-none focus:ring-0 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 dark:focus:border-neutral-500 dark:focus:bg-neutral-900">
                                @error('email')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex items-center gap-4 pt-1">
                                <button type="submit" class="rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-700 dark:bg-white dark:text-black dark:hover:bg-neutral-200">
                                    Save changes
                                </button>
                                @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-xs text-neutral-500 dark:text-neutral-400">Saved.</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="grid grid-cols-1 gap-x-16 gap-y-6 py-10 md:grid-cols-3">
                    <div>
                        <h2 class="text-sm font-semibold text-neutral-900 dark:text-white">Password</h2>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Update your password to keep your account secure.</p>
                    </div>
                    <div class="md:col-span-2">
                        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                            @csrf
                            @method('put')

                            <div>
                                <label for="update_password_current_password" class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1.5">Current Password</label>
                                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 transition focus:border-neutral-400 focus:bg-white focus:outline-none focus:ring-0 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 dark:focus:border-neutral-500">
                                @if ($errors->updatePassword->has('current_password'))<p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('current_password') }}</p>@endif
                            </div>

                            <div>
                                <label for="update_password_password" class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1.5">New Password</label>
                                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 transition focus:border-neutral-400 focus:bg-white focus:outline-none focus:ring-0 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 dark:focus:border-neutral-500">
                                @if ($errors->updatePassword->has('password'))<p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('password') }}</p>@endif
                            </div>

                            <div>
                                <label for="update_password_password_confirmation" class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1.5">Confirm New Password</label>
                                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 transition focus:border-neutral-400 focus:bg-white focus:outline-none focus:ring-0 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 dark:focus:border-neutral-500">
                                @if ($errors->updatePassword->has('password_confirmation'))<p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('password_confirmation') }}</p>@endif
                            </div>

                            <div class="flex items-center gap-4 pt-1">
                                <button type="submit" class="rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-700 dark:bg-white dark:text-black dark:hover:bg-neutral-200">
                                    Update password
                                </button>
                                @if (session('status') === 'password-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-xs text-neutral-500 dark:text-neutral-400">Saved.</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delete Account Section -->
                <div class="grid grid-cols-1 gap-x-16 gap-y-6 py-10 md:grid-cols-3">
                    <div>
                        <h2 class="text-sm font-semibold text-red-600 dark:text-red-400">Delete Account</h2>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Permanently remove your account and all associated data.</p>
                    </div>
                    <div class="md:col-span-2">
                        <div class="rounded-lg border border-red-200 bg-red-50 p-5 dark:border-red-900/50 dark:bg-red-950/20">
                            <p class="text-sm text-red-700 dark:text-red-300 leading-relaxed">
                                Once your account is deleted, all of its resources and data will be permanently removed. This action cannot be undone.
                            </p>
                            <div class="mt-4">
                                <button
                                    x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                                    class="rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:border-red-800 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-950/40">
                                    Delete account
                                </button>
                            </div>
                        </div>

                        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                @csrf
                                @method('delete')
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Are you sure?</h2>
                                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">This action is irreversible. Enter your password to confirm.</p>
                                <div class="mt-5">
                                    <input id="password" name="password" type="password" placeholder="Your password"
                                        class="w-full rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2.5 text-sm focus:outline-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                    @if ($errors->userDeletion->has('password'))<p class="mt-1.5 text-xs text-red-500">{{ $errors->userDeletion->first('password') }}</p>@endif
                                </div>
                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" x-on:click="$dispatch('close')" class="rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700">Cancel</button>
                                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Delete account</button>
                                </div>
                            </form>
                        </x-modal>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
