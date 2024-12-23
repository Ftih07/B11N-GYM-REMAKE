import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Aktifkan mode gelap menggunakan class
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: "#dc030a", // Warna utama
                "primary-dark": "rgb(135, 6, 12)", // Warna utama gelap
                "text-dark": "#0a0a0a", // Warna teks gelap
                "text-light": "#737373", // Warna teks terang
                "extra-light": "#e5e5e5", // Warna ekstra terang
                white: "#ffffff", // Warna putih
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans], // Font bawaan (Figtree)
                header: ["Oswald", "sans-serif"], // Font header
                body: ["Poppins", "sans-serif"], // Font body
            },
            maxWidth: {
                screen: "1200px", // Max width custom
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};
