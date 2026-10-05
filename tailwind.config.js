/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./app/**/*.php",
  ],
  theme: {
    extend: {
      fontFamily: {
        kid: ['Nunito', 'Quicksand', 'sans-serif'],
      },
      colors: {
        kid: {
          blue:   '#7EC8E3',
          mint:   '#A8E6CF',
          yellow: '#FFD97D',
          coral:  '#FF8B94',
          purple: '#B39DDB',
          bg:     '#F7FBFF',
          text:   '#2E3A59',
        },
      },
      borderRadius: {
        kid: '1.25rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}