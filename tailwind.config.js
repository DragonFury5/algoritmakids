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
    kid: ['Fredoka', 'Nunito', 'sans-serif'],
    body: ['Nunito', 'sans-serif'],
},
colors: {
    kid: {
        // Keep the OLD keys, just change the hex.
        // This means no view edits needed.
        blue:   '#FF6B6B',  // primary (coral red)
        coral:  '#E85555',  // darker coral for hover
        mint:   '#4ECDC4',  // secondary (teal)
        teal:   '#4ECDC4',  // alias for new usages
        yellow: '#FCD34D',  // accent
        purple: '#9B7EDE',
        bg:     '#FFF8F0',
        text:   '#2D2A26',
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