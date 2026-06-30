/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        gold: {
          DEFAULT: '#ffd300',
          light: '#ffe44d',
          dark: '#ccaa00',
        },
        dark: '#262b32',
        darker: '#1a1e24',
        black: '#090c11',
        gray: {
          custom: '#757b81',
          light: '#9ca3af',
        }
      },
      backgroundImage: {
        'gradient-main': 'linear-gradient(135deg, #090c11 0%, #262b32 50%, #090c11 100%)',
        'gradient-gold': 'linear-gradient(135deg, #ffd300, #ccaa00)',
        'gradient-card': 'linear-gradient(145deg, rgba(38, 43, 50, 0.95), rgba(9, 12, 17, 0.95))',
        'gradient-hero': 'linear-gradient(180deg, rgba(9, 12, 17, 0) 0%, #090c11 100%)',
        'gradient-accent': 'linear-gradient(135deg, #ffd300 0%, #ffe44d 50%, #ffd300 100%)',
        'gradient-sidebar': 'linear-gradient(180deg, #262b32 0%, #090c11 100%)',
      },
      fontFamily: {
        primary: ['Outfit', 'sans-serif'],
        secondary: ['Inter', 'sans-serif'],
      },
      borderRadius: {
        'sm': '6px',
        'md': '12px',
        'lg': '20px',
        'xl': '28px',
      },
      boxShadow: {
        'sm': '0 2px 8px rgba(0, 0, 0, 0.3)',
        'md': '0 4px 20px rgba(0, 0, 0, 0.4)',
        'lg': '0 8px 40px rgba(0, 0, 0, 0.5)',
        'gold': '0 4px 20px rgba(255, 211, 0, 0.25)',
        'gold-lg': '0 8px 40px rgba(255, 211, 0, 0.35)',
      },
      backdropBlur: {
        'glass': '12px',
      }
    },
  },
  plugins: [],
}
