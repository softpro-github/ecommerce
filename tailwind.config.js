import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                script: ['Sail', 'cursive'],
                accent: ['Buenard', 'serif'],
            },
            colors: {
                accent: {
                    DEFAULT: '#1346af',
                    dark: '#0e3689',
                },
                ink: '#2b2b2b',
                muted: '#8f8f8f',
                panel: '#f0f0f0',
            },
        },
    },

    plugins: [forms],
};
