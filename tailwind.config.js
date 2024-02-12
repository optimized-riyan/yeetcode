/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
        colors: {
            leetcode: {
                background: '#1A1A1A',
                backgroundlight: '#282828',
                backgroundlighter: '#3E3E3E',
                textdark: '#3D3D3E',
                text: '#C2C4C7',
                textlight: '#FFFFFF',
                green: '#28934D',
                purple: '#8748A7',
                blue: '#124F8C',
                red: '#F5365C',
                yellow: '#FFC01E',
                navbar: '#282828',
            }
        }
    },
  },
  plugins: [],
}

