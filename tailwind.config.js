module.exports = {
    mode: 'jit',
    purge: {
        content: [
            './templates/**/*.twig',
            './src/**/*.{js,jsx,ts,tsx,vue}'
        ],
        options: {
    safelist: [
        /data-theme$/,
    ],
},
    },
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {},
    },
    variants: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('daisyui')
    ],
}
