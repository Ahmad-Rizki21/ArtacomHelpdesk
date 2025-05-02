import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/filament/**/*.blade.php",
        './vendor/kenepa/banner/resources/**/*.php',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}