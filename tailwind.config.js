import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                leetcode: {
                    background: "#1A1A1A",
                    backgroundlight: "#282828",
                    backgroundlighter: "#3E3E3E",
                    textdark: "#3D3D3E",
                    text: "#C2C4C7",
                    textlight: "#FFFFFF",
                    green: "#28934D",
                    "green-light": "#38CE6D",
                    purple: "#8748A7",
                    blue: "#124F8C",
                    red: "#F5365C",
                    yellow: "#FFC01E",
                    navbar: "#282828",
                },
            },
        },
    },

    plugins: [forms],
};
