(() => {
    const script = document.currentScript;
    const base = new URL('../', script.src);
    const button = document.getElementById('pwa-install');
    const help = document.getElementById('pwa-install-help');
    const standalone = () => window.matchMedia('(display-mode: standalone)').matches || navigator.standalone;
    let installPrompt;
    const hideInstall = () => {
        button.hidden = true;
        button.style.display = 'none';
        help.hidden = true;
    };

    window.addEventListener('beforeinstallprompt', (event) => {
        if (standalone()) return;
        event.preventDefault();
        installPrompt = event;
        button.hidden = false;
        button.style.removeProperty('display');
    });
    button.addEventListener('click', async () => {
        if (!installPrompt) return;
        const prompt = installPrompt;
        installPrompt = null;
        hideInstall();
        try {
            await prompt.prompt();
            await prompt.userChoice;
        } catch (error) {
            console.warn('Hadith installation was unavailable.', error);
        }
    });
    window.addEventListener('appinstalled', hideInstall);
    if (!standalone() && (/iPad|iPhone|iPod/.test(navigator.userAgent) ||
        (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1))) help.hidden = false;

    if ('serviceWorker' in navigator && window.isSecureContext) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register(new URL('sw.js', base), { scope: base.pathname, updateViaCache: 'none' })
                .catch((error) => console.warn('Hadith offline setup failed.', error));
        });
    }
})();
