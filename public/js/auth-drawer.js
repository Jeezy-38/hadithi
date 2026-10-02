/* Auth drawer: fungua/funga, Esc, backdrop, focus trap, na toast ya "Karibu". */
(function () {
    const drawer = document.getElementById('auth-drawer');
    const backdrop = document.querySelector('.auth-backdrop');
    if (!drawer || !backdrop) return;

    const triggers = document.querySelectorAll('[data-auth-open]');
    const focusable = 'a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])';
    let lastFocus = null;

    const setExpanded = (value) => triggers.forEach((t) => t.setAttribute('aria-expanded', String(value)));

    function open() {
        lastFocus = document.activeElement;
        backdrop.hidden = false;
        requestAnimationFrame(() => {
            backdrop.classList.add('is-open');
            drawer.classList.add('is-open');
        });
        drawer.setAttribute('aria-hidden', 'false');
        document.body.classList.add('auth-drawer-open');
        setExpanded(true);
        setTimeout(() => (drawer.querySelector('#auth-panel-' + (drawer.getAttribute('data-auth-mode') || 'login') + ' input:not([type="hidden"]), .auth-links a, .auth-close') || drawer).focus(), 60);
    }

    function close() {
        drawer.classList.remove('is-open');
        backdrop.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('auth-drawer-open');
        setExpanded(false);
        setTimeout(() => { backdrop.hidden = true; }, 250);
        if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
    }

    triggers.forEach((t) => t.addEventListener('click', open));
    document.querySelectorAll('[data-auth-close]').forEach((el) => el.addEventListener('click', close));

    document.addEventListener('keydown', (event) => {
        if (!drawer.classList.contains('is-open')) return;
        if (event.key === 'Escape') {
            close();
            return;
        }
        if (event.key !== 'Tab') return;
        const items = Array.from(drawer.querySelectorAll(focusable));
        if (!items.length) return;
        const first = items[0];
        const last = items[items.length - 1];
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    });

    // Fungua moja kwa moja: /login, ?login=1, au kosa la kuingia.
    if (drawer.hasAttribute('data-auto-open')) {
        open();
        try {
            const url = new URL(window.location.href);
            if (url.searchParams.has('login')) {
                url.searchParams.delete('login');
                history.replaceState(history.state, '', url.pathname + url.search + url.hash);
            }
        } catch (_) {}
    }

    // --- Hali ya drawer: ingia | jisajili | umesahau ---
    const tabs = drawer.querySelectorAll('.auth-tab');
    function setMode(mode, focus) {
        drawer.setAttribute('data-auth-mode', mode);
        tabs.forEach((tab) => {
            const active = tab.dataset.authTab === mode;
            tab.setAttribute('aria-selected', String(active));
            tab.tabIndex = active ? 0 : -1;
        });
        if (mode === 'forgot') {
            const from = drawer.querySelector('#login-email');
            const to = drawer.querySelector('#forgot-email');
            if (from && to && !to.value) to.value = from.value;
        }
        if (focus) {
            const field = drawer.querySelector('#auth-panel-' + mode + ' input:not([type="hidden"]):not([type="checkbox"]), #auth-panel-' + mode + ' button');
            if (field) field.focus();
        }
    }
    if (drawer.querySelector('[data-auth-tab]')) {
        setMode(drawer.getAttribute('data-auth-mode') || 'login', false);
        drawer.querySelectorAll('[data-auth-tab]').forEach((btn) =>
            btn.addEventListener('click', () => setMode(btn.dataset.authTab, true)));
        tabs.forEach((tab) => tab.addEventListener('keydown', (event) => {
            if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
            const next = tab.dataset.authTab === 'login' ? 'register' : 'login';
            setMode(next, false);
            drawer.querySelector('.auth-tab[data-auth-tab="' + next + '"]').focus();
        }));
        // Kosa likirudi kutoka server, weka focus kwenye sehemu yenye kosa.
        const invalid = drawer.querySelector('.auth-form input[aria-invalid="true"]');
        if (invalid) setTimeout(() => invalid.focus(), 400);
    }

    // --- Onyesha / ficha nenosiri ---
    document.querySelectorAll('[data-auth-eye]').forEach((btn) => btn.addEventListener('click', () => {
        const input = btn.parentElement.querySelector('input');
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-pressed', String(show));
        btn.setAttribute('aria-label', show ? 'Ficha nenosiri' : 'Onyesha nenosiri');
    }));

    // --- Nguvu ya nenosiri ---
    document.querySelectorAll('[data-auth-strength]').forEach((strength) => {
        const input = document.getElementById(strength.dataset.for);
        if (!input) return;
        input.addEventListener('input', () => {
            const v = input.value;
            let level = 0;
            if (v.length >= 8) level++;
            if (/[a-z]/i.test(v) && /\d/.test(v)) level++;
            if (v.length >= 12) level++;
            if (/[^a-z0-9]/i.test(v) || (/[a-z]/.test(v) && /[A-Z]/.test(v))) level++;
            strength.dataset.level = v ? String(Math.max(level, 1)) : '0';
        });
    });

    // --- Spinner wakati fomu inatumwa ---
    document.querySelectorAll('.auth-form').forEach((form) => form.addEventListener('submit', () => {
        const btn = form.querySelector('.auth-submit');
        if (btn) btn.classList.add('is-loading');
    }));

    const toast = document.querySelector('[data-auth-toast]');
    if (toast) setTimeout(() => toast.classList.add('is-hidden'), 4000);
})();
