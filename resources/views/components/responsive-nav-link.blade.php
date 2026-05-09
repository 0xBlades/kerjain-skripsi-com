@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-sky-500 text-start text-base font-semibold text-sky-700 bg-sky-50 dark:text-sky-200 dark:bg-sky-950/50 focus:outline-none focus:text-sky-800 focus:bg-sky-100 focus:border-sky-600 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-600 dark:text-slate-300 hover:text-sky-700 dark:hover:text-sky-200 hover:bg-sky-50 dark:hover:bg-sky-950/40 hover:border-sky-200 focus:outline-none focus:text-sky-700 focus:bg-sky-50 focus:border-sky-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
