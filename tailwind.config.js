import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                display: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                night: '#050614',
                ember: '#FF7A18',
                lotus: '#FDB2FF',
                abyss: '#0F172A',
                citrus: '#FFB347',
                ocean: '#50C4F5',
                blossom: '#FFE7F3',
                mint: '#CBF6E1',
                // Dark mode palette
                dm: {
                    bg:      '#1a1d23',
                    card:    '#21252e',
                    cardHov: '#272b35',
                    border:  '#2e3340',
                    text:    '#e2e5ec',
                    muted:   '#8b92a5',
                    accent:  '#38bdf8',
                    accentHov: '#7dd3fc',
                },
            },
            boxShadow: {
                glow: '0 25px 70px -35px rgba(255, 179, 71, 0.5)',
            },
            backgroundImage: {
                'mesh-day': 'radial-gradient(circle at 20% 20%, rgba(255, 183, 77, 0.35), transparent 50%), radial-gradient(circle at 80% 0%, rgba(80, 196, 245, 0.35), transparent 45%), radial-gradient(circle at 10% 80%, rgba(203, 246, 225, 0.4), transparent 55%)',
                'grid-slate': 'linear-gradient(rgba(148,163,184,0.12) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,0.08) 1px, transparent 1px)',
            },
        },
    },

    plugins: [forms, typography],
};
