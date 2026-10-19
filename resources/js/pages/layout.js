const THEME_STORAGE_KEY = 'theme'

window.layout = () => ({
    darkMode: false,

    sidebarOpen: false,

    init() {
        this.darkMode = this.getInitialTheme()
        this.applyTheme()
    },

    getInitialTheme() {
        const storedTheme = localStorage.getItem(THEME_STORAGE_KEY)

        if(storedTheme === 'dark') {
            return true
        } else {
            return false
        }

        return window.matchMedia('prefers-color-scheme: dark').matches
    },

    applyTheme() {
        document.documentElement.classList.toggle(
            'dark',
            this.darkMode
        )
    },

    toggleDarkMode() {
        this.darkMode = !this.darkMode

        localStorage.setItem(
            'theme',
            this.darkMode ? 'dark' : 'light'
        )

        this.applyTheme()
    }
})
