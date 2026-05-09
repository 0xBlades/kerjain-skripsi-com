<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Hero Card -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-32"></div>
                <div class="px-6 pb-6">
                    <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-12 mb-4 gap-4">
                        <div class="relative">
                            @if ($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full border-4 border-white dark:border-gray-800 object-cover shadow-md">
                            @else
                                <div class="w-24 h-24 rounded-full border-4 border-white dark:border-gray-800 bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center shadow-md">
                                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-300">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="text-center sm:text-left mb-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        </div>
                        <div class="sm:ml-auto mt-2 sm:mt-0 mb-1">
                            @if ($user->google_id)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                                    </svg>
                                    Google Account
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    Email Account
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Profile Information -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Update Password -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 sm:p-8 border border-red-100 dark:border-red-900/30">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
