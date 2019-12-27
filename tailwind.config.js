module.exports = {
    theme: {
        extend: {},
        inset: {},
        screens: {
            'sm': { 'min': '640px', 'max': '767px' },
            'md': { 'min': '768px', 'max': '1399px' },
            'lg': { 'min': '1400px' }
        },
        minHeight: {
            '10': '2.5rem',
        },
        minWidth: {
            'xp-320': '320px',
        },
        maxHeight: {
            '0': '0',
            '1/4': '25%',
            '1/2': '50%',
            '3/4': '75%',
            'full': '100%',
            'vh-96': '96vh',
            'vh-60': '60vh',
        },
        extend: {
            colors: {
                'ching': '#177cb0',
                'pink': '#fb7299',
                'black': '#212121',
                'grey': '#999999',
                'offwhite': '#f6f6f6',
            }
        },
    },
    variants: {},
    plugins: [
        function ({ addUtilities }) {
            const newUtilities = {
                '.rotate-0': {
                    transform: 'rotate(0deg)',
                },
                '.rotate-45': {
                    transform: 'rotate(45deg)',
                },
                '.rotate-90': {
                    transform: 'rotate(90deg)',
                },
                '.rotate-180': {
                    transform: 'rotate(180deg)',
                },
                '.rotate-270': {
                    transform: 'rotate(270deg)',
                },
            }

            addUtilities(newUtilities, ['responsive', 'hover'])
        }
    ]
}
