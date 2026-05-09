@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-sky-500 text-sm font-semibold leading-5 text-sky-600 dark:text-sky-300 focus:outline-none focus:border-sky-600 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-slate-500 dark:text-slate-300 hover:text-sky-600 dark:hover:text-sky-200 hover:border-sky-200 dark:hover:border-sky-700 focus:outline-none focus:text-sky-600 focus:border-sky-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
