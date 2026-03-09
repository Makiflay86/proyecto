function applyTheme() {
    const saved      = localStorage.getItem('theme');
    const preferDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    if (saved === 'dark' || (!saved && preferDark)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

window.toggleTheme = function () {
    const dark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', dark ? 'dark' : 'light');
};

document.addEventListener('livewire:navigated', applyTheme);
