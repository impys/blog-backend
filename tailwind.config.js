module.exports = {
    theme: {
        extend: {},
        inset: {},
        minHeight: {
            '10': '2.5rem',
        },
        maxHeight: {
            '100': '25rem',
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
