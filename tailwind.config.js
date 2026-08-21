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
            colors: {
                primary: {
                    50: '#eef4ff',
                    100: '#d9e7ff',
                    200: '#bcd3ff',
                    300: '#8fb4ff',
                    400: '#5a8cff',
                    500: '#2f63ff',
                    600: '#003399',
                    700: '#002b80',
                    800: '#001f66',
                    900: '#001447',
                },
            },

            fontFamily: {
                // sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },

            boxShadow: {
                soft: '0 4px 20px rgba(0,0,0,0.05)',
            },
        },
    },

    plugins: [forms],
};
