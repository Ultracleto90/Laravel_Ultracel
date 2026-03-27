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
            colors: {
                'azul-oscuro': '#005187',
                'azul-medio': '#4d82bc',
                'azul-claro': '#84b6f4',
                'azul-muy-claro': '#c4dafa',
                'blanco-azulado': '#fcffff',
            },
            fontFamily: {
                sans: ['Readex Pro', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};