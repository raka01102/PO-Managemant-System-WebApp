window.layout = () => ({
    darkMode:
        localStorage.getItem('theme') === 'dark' ||
        (
            !localStorage.getItem('theme')
            &&
            window.matchMedia('(prefers-color-scheme: dark)').matches
        ),

    sidebarOpen: false,

    init() {
        this.applyTheme()
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
