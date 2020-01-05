module.exports = {
    theme: {
        screens: {
            'lg': { 'min': '1300px' }
        },
        minHeight: {
            '10': '2.5rem',
            '8': '2em',
            '7': '1.75em',
            '6': '1.5em',
        },
        maxHeight: {
            '0': '0',
            '1/4': '25%',
            '1/2': '50%',
            '3/4': '75%',
            'full': '100%',
            'vh-96': '96vh',
            'vh-80': '80vh',
            'vh-60': '60vh',
            'vh-45': '45vh',
        },
        boxShadow: {
            'focus': '0 4px 6px 0 rgba(32,33,36,0.28);'
        },
        extend: {
            colors: {
                'ching': 'var(--color-ching)',
                'pink': 'var(--color-pink)',
                'black': 'var(--color-black)',
                'grey': 'var(--color-grey)',
                'offwhite': 'var(--color-offwhite)',
            },
            borderRadius: {
                '12': '12px',
                '16': '16px',
                '18': '18px',
                '20': '20px',
                '22': '22px',
            },
            padding: {
                '8-px': '8px',
                '34-px': '34px',
            },
            margin: {
                '2-px': '2px',
                '34-px': '34px',
            },
            inset: {
                '-3': '-0.75rem',
                '-1': '-0.25rem',
                '12': '3rem',
            },
            lineHeight: {
                '7': '1.75rem',
                '12': '3rem',
            }
        },
    },
    variants: {
        borderWidth: ['responsive', 'first', 'last', 'hover', 'focus'],
    },
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
                '.transition-box-shadow-05': {
                    transition: '.5s box-shadow ease-in-out',
                },
                '.transition-background-color-03': {
                    transition: '.3s background-color ease-in-out',
                },
                '.input-caret-color-ching': {
                    'caret-color': 'var(--color-ching)',
                },
            }

            addUtilities(newUtilities, ['responsive', 'hover'])
        }
    ]
}
