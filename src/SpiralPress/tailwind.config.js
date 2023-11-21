/** @type {import('tailwindcss').Config} */
module.exports = {
  plugins: [ require('daisyui')],
  content: [
    './resources/**/*.php',
  ],
  theme: {
    extend: {
      animation: {
        "wobble-ver-right": "wobble-ver-right 0.6s ease   both"
      },
      keyframes: {
        "wobble-ver-right": {
          "0%,to": {
            transform: "translateY(0) rotate(0)",
            "transform-origin": "50% 50%"
          },
          "15%": {
              transform: "translateY(-15px) rotate(6deg)" // 30pxから15pxへ変更
          },
          "30%": {
              transform: "translateY(7.5px) rotate(-6deg)" // 15pxから7.5pxへ変更
          },
          "45%": {
              transform: "translateY(-7.5px) rotate(3.6deg)" // 15pxから7.5pxへ変更
          },
          "60%": {
              transform: "translateY(4.5px) rotate(-2.4deg)" // 9pxから4.5pxへ変更
          },
          "75%": {
              transform: "translateY(-3px) rotate(1.2deg)" // 6pxから3pxへ変更
          }
        }
      }
    }
  }
};
