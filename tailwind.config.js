module.exports = {
  purge: [],
  darkMode: false, // or 'media' or 'class'
  theme: {
    fontFamily: {
      body: [
        'ヒラギノ角ゴシック'
      ]
    },
    extend: {
      ringWidth: ['hover', 'active'],
      colors: {
        red: {
          normal: '#EB0009'
        },
        orange: {
          normal: '#FCA417'
        },
        blue: {
          light: '#CFF1F7',
          sky: '#82D4E3',
          normal: '#53B6C9',
          dark: '#0C7286',
          deep: '#105E81'
        },
        green: {
          normal: '#81CE76',
        },
        white: { 
          light: '#F5F2F2',
          normal: '#ffffff',
          shallowDark: '#E5E5E5',
          dark: '#C4C4C4',
          deepDark: '#B8B8B8'
        },
        beige: {
          normal: '#D9C4B1'
        }
      },
      spacing: {
        '1/10': '10%',
        '90': '22rem',
        '100': '26rem',
        '110': '32rem',
        '120': '40rem',
        '130': '50rem',
        '140': '60rem'
      },
      maxWidth: {
        '1': '1rem',
        '2': '2rem',
        '3': '3rem',
        '4': '4rem',
        '5': '5rem',
        '6': '6rem',
        '6.25': '6.25rem',
        '7': '7rem',
        '8': '8rem',
        '9': '9rem',
        '10': '10rem',
        '11': '11rem',
        '12': '12rem',
        '13': '13rem',
        '14': '14rem',
        '15': '15rem',
        '16': '16rem',
        '20': '20rem',
        '110': '32rem',
        '120': '40rem',
        '130': '50rem',
      },
      minWidth: {
        '0': '0',
        '640': '640px',
        '1/4': '25%',
        '1/2': '50%',
        '3/4': '75%',
        'full': '100%',
      },
      screens: {
        'mini': '450px',
      },
      boxShadow: {
        'custom': '0 5px 0 0 rgba(0, 0, 0, 0.2)',
      }
    }
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
