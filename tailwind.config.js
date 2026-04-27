import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gold: {
                    50:  '#fdf8ec',
                    100: '#f9edcc',
                    200: '#f2d98a',
                    300: '#ecc44d',
                    400: '#d4a93a',
                    500: '#C9A84C',
                    600: '#b08a2e',
                    700: '#8a6a22',
                    800: '#664f1a',
                    900: '#433312',
                },
            },
        },
    },

    plugins: [forms],
};
