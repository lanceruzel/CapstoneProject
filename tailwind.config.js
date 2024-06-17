/** @type {import('tailwindcss').Config} */
export default {
  presets: [
    require("./vendor/wireui/wireui/tailwind.config.js")
  ],
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/**/*.blade.php", 
    "./resources/**/**/**/*.blade.php", 
    "./resources/views/components/**/*.blade.php", 
    "./resources/views/livewire/**/*.blade.php", 
    "./vendor/wireui/wireui/src/*.php",
    "./vendor/wireui/wireui/ts/**/*.ts",
    "./vendor/wireui/wireui/src/WireUi/**/*.php",
    "./vendor/wireui/wireui/src/Components/**/*.php",
  ],
  theme: {
    extend: {
      fontFamily:{
        inter:['Inter', 'system-ui', 'sans-serif']
      },
      backgroundImage: {
        // 'login-pattern': "url('/public/assets/bg/login-bg.jpg')",
      }
    },
  },
  plugins: [],
}

