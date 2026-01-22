import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    50: '#E6E8ED',
                    100: '#C1C7D3',
                    200: '#98A3B6',
                    300: '#717F9A',
                    400: '#4F6081',
                    500: '#2E4369',
                    600: '#001F3F', // Primary Navy
                    700: '#001933',
                    800: '#001226',
                    900: '#000C1A',
                },
                gold: {
                    50: '#FFFAF0',
                    100: '#FDF5D3',
                    200: '#FCEEA7',
                    300: '#FADD7A',
                    400: '#F9CC4D',
                    500: '#F7B500', // Primary Gold
                    600: '#C79100',
                    700: '#966D00',
                    800: '#644900',
                    900: '#332500',
                },
            },
        },
    },

    plugins: [forms, typography],
};
