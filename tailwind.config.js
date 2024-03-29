const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            screens: {
                'header': '860px',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require("daisyui"),
    ],

    daisyui: {
        styled: true,
        themes: [
            {
                mytheme: {
                    "primary": "#4338CA",
                    "secondary": "#D926A9",
                }
            }
        ],
        base: false,
        utils: false,
        logs: true,
        rtl: false,
        //darkTheme: "dark",
      },
};
