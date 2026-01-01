/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,jsx,ts,tsx}",
    "./public/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#9333EA',
        'primary-dark': '#7E22CE',
      }
    },
  },
  plugins: [],
} 