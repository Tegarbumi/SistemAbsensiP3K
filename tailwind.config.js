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
                sans: ['"IBM Plex Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gov: {
                    blue: 'var(--gov-blue)',
                    'blue-hover': 'var(--gov-blue-hover)',
                    'blue-subtle': 'var(--gov-blue-subtle)',
                    border: 'var(--gov-border)',
                    green: 'var(--gov-green)',
                    'green-subtle': 'var(--gov-green-subtle)',
                    red: 'var(--gov-red)',
                    'red-subtle': 'var(--gov-red-subtle)',
                },
            },
            borderRadius: {
                DEFAULT: '4px',
                sm: '2px',
                md: '6px',
                lg: '8px',
            },
        },
    },

    plugins: [forms],
};
