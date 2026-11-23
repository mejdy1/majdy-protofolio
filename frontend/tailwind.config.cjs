/** @type {import('tailwindcss').Config} */
// Only Tailwind's base reset (preflight) is used; the design system lives in src/style.css.
module.exports = {
  content: ['./index.html', './src/**/*.{vue,ts}'],
  theme: { extend: {} },
  plugins: [],
}
