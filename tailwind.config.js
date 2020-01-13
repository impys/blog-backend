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

        extend: {
            colors: {
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
                '40-vh': '40vh',
                '50-vh': '50vh',
                '40-percent': '40%',
                '50-percent': '50%',
            },
            inset: {
                '1': '0.25rem',
                '-1': '-0.25rem',
                '-3': '-0.75rem',
                '-5': '-1.25rem',
                '12': '3rem',
            },
            lineHeight: {
                '7': '1.75rem',
                '12': '3rem',
            },
            height: {
                '80': '20rem',
                '100': '25rem',
                '120': '30rem',
            },
            boxShadow: {
                'focus': '0 4px 6px 0 rgba(32,33,36,0.28);'
            },
            borderWidth: {
                '.5': '0.5px',
            }
        },
    },
    variants: {
        borderWidth: ['responsive', 'first', 'last', 'hover', 'focus'],
    }
}
