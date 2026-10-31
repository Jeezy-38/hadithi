/*
 * Learning Centre: mazoezi ya kuficha maneno, quiz, kuhifadhi (kurudia kwa nafasi),
 * na vitufe vya "Nimejifunza" / "Ongeza kwenye kuhifadhi".
 * Maandishi yote yanapita BaytI18n (sw / en / ar).
 */
(() => {
    'use strict';

    const isAuth = () => document.body?.dataset.auth === '1';
    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const T = (k, v) => (window.BaytI18n ? window.BaytI18n.t(k, v) : k);
    const applyI18n = (root) => { if (window.BaytI18n) window.BaytI18n.apply(root || document); };
    const el = (tag, cls, text) => { const e = document.createElement(tag); if (cls) e.className = cls; if (text !== undefined) e.textContent = text; return e; };
    const i18nEl = (tag, cls, key, vars) => {
        const e = el(tag, cls, T(key, vars));
        e.setAttribute('data-i18n', key);
        if (vars) e.setAttribute('data-i18n-vars', JSON.stringify(vars));
        return e;
    };

    const post = async (url, body) => {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() },
            body: body ? JSON.stringify(body) : undefined,
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    };
    const goLogin = () => { window.location.href = '/login'; };

    // ---- Maneno yaliyofichwa (mazoezi na kadi) ----
    const mulberry = (a) => () => {
        a |= 0; a = (a + 0x6D2B79F5) | 0;
        let t = Math.imul(a ^ (a >>> 15), 1 | a);
        t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
        return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
    };

    function renderMasked(container, text, ratio, seed) {
        container.textContent = '';
        const words = text.split(/\s+/).filter(Boolean);
        const rnd = mulberry(Number(seed) || 1);
        const order = words.map((_, i) => i).sort(() => rnd() - 0.5);
        const hide = new Set(order.slice(0, Math.round(words.length * ratio)));
        words.forEach((w, i) => {
            const span = el('span', 'lc-word', w + ' ');
            if (hide.has(i)) {
                span.classList.add('is-hidden');
                span.setAttribute('role', 'button');
                span.setAttribute('tabindex', '0');
                const reveal = () => span.classList.remove('is-hidden');
                span.addEventListener('click', reveal);
                span.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); reveal(); } });
            }
            container.appendChild(span);
        });
    }

    // ---- Ukurasa wa somo ----
    function initPractice() {
        const box = document.querySelector('[data-lc-practice]');
        if (!box) return;
        const target = box.querySelector('[data-lc-masked]');
        const range = box.querySelector('[data-lc-level]');
        const out = box.querySelector('[data-lc-level-out]');
        const draw = () => {
            const ratio = Number(range.value) * 0.25;
            out.textContent = Math.round(ratio * 100) + '%';
            renderMasked(target, box.dataset.text, ratio, box.dataset.seed);
        };
        range.addEventListener('input', draw);
        draw();
    }

    function initToggleButtons() {
        const setLabel = (btn, key, fallback) => {
            const span = btn.querySelector('span');
            span.setAttribute('data-i18n', key);
            span.textContent = T(key) === key ? fallback : T(key);
        };
        document.querySelectorAll('[data-lc-complete]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                if (!isAuth()) return goLogin();
                btn.disabled = true;
                try {
                    const r = await post(btn.dataset.lcComplete);
                    btn.classList.toggle('is-on', r.completed);
                    setLabel(btn, r.completed ? 'pg_lc_completed' : 'pg_lc_complete', r.completed ? 'Nimekamilisha ✓' : 'Nimejifunza ✓');
                } catch (_) {} finally { btn.disabled = false; }
            });
        });
        document.querySelectorAll('[data-lc-card]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                if (!isAuth()) return goLogin();
                if (btn.dataset.on === '1') return;
                btn.disabled = true;
                try {
                    await post(btn.dataset.lcCard);
                    btn.dataset.on = '1';
                    btn.classList.add('is-on');
                    setLabel(btn, 'pg_lc_card_added', 'Imo kwenye kuhifadhi ✓');
                } catch (_) {} finally { btn.disabled = false; }
            });
        });
    }

    // ---- Quiz ----
    function initQuiz() {
        const root = document.querySelector('[data-lc-quiz]');
        if (!root) return;
        let questions = [], idx = 0, score = 0;

        const showResult = () => {
            root.textContent = '';
            const card = el('div', 'lc-card lc-result');
            card.appendChild(i18nEl('h2', 'lc-result-title', 'pg_lc_score', { s: score, t: questions.length }));
            const row = el('div', 'lc-actions');
            const retry = i18nEl('a', 'lc-btn', 'pg_lc_retry');
            retry.href = root.dataset.retry;
            row.appendChild(retry);
            if (!isAuth()) row.appendChild(i18nEl('p', 'lc-hint-text', 'pg_lc_save_guest_hint'));
            card.appendChild(row);
            root.appendChild(card);
            applyI18n(root);
            if (isAuth()) post('/jifunze/shughuli').catch(() => {});
        };

        const showQuestion = () => {
            const q = questions[idx];
            root.textContent = '';
            const card = el('div', 'lc-card lc-question');
            card.appendChild(i18nEl('p', 'lc-q-count', 'pg_lc_q_of', { n: idx + 1, t: questions.length }));
            card.appendChild(i18nEl('h2', 'lc-q-prompt', q.prompt_key));
            const text = el('p', 'lc-q-text', q.text);
            if (q.type === 'title') text.classList.add('is-title');
            card.appendChild(text);

            const list = el('div', 'lc-options');
            const feedback = el('div', 'lc-feedback');
            let answered = false;
            q.options.forEach((opt, i) => {
                const b = el('button', 'lc-option', opt);
                b.type = 'button';
                b.addEventListener('click', () => {
                    if (answered) return;
                    answered = true;
                    const ok = i === q.answer;
                    if (ok) score++;
                    list.querySelectorAll('.lc-option').forEach((x, j) => {
                        x.disabled = true;
                        if (j === q.answer) x.classList.add('is-right');
                        else if (j === i) x.classList.add('is-wrong');
                    });
                    feedback.appendChild(i18nEl('strong', ok ? 'is-ok' : 'is-bad', ok ? 'pg_lc_correct' : 'pg_lc_wrong'));
                    const link = el('a', 'lc-link', q.ref.label);
                    link.href = q.ref.url;
                    feedback.append(' ', link);
                    const next = i18nEl('button', 'lc-btn', idx + 1 < questions.length ? 'pg_lc_next_q' : 'pg_lc_finish');
                    next.type = 'button';
                    next.addEventListener('click', () => { idx++; idx < questions.length ? showQuestion() : showResult(); });
                    feedback.appendChild(next);
                    applyI18n(card);
                });
                list.appendChild(b);
            });
            card.append(list, feedback);
            root.appendChild(card);
            applyI18n(root);
        };

        fetch(root.dataset.url, { headers: { 'Accept': 'application/json' } })
            .then((r) => r.json())
            .then((d) => {
                questions = d.questions || [];
                if (!questions.length) {
                    root.textContent = '';
                    root.appendChild(i18nEl('p', 'lc-note', 'pg_lc_quiz_empty'));
                    applyI18n(root);
                    return;
                }
                showQuestion();
            })
            .catch(() => { root.textContent = ''; root.appendChild(i18nEl('p', 'lc-note', 'pg_lc_quiz_empty')); applyI18n(root); });
    }

    // ---- Kuhifadhi ----
    function initMemorize() {
        const root = document.querySelector('[data-lc-memorize]');
        if (!root) return;
        let cards = [], i = 0;

        const done = () => {
            root.textContent = '';
            root.appendChild(i18nEl('p', 'lc-note', cards.length ? 'pg_lc_mem_done' : 'pg_lc_mem_none'));
            applyI18n(root);
        };

        const show = () => {
            if (i >= cards.length) return done();
            const c = cards[i];
            root.textContent = '';
            const card = el('div', 'lc-card');
            card.appendChild(i18nEl('p', 'lc-q-count', 'pg_lc_mem_card_n', { n: i + 1, t: cards.length }));
            card.appendChild(el('h2', 'lc-q-prompt', c.title));
            const arabic = el('p', 'arabic arabic-text font-arabic lc-arabic lc-masked');
            arabic.lang = 'ar'; arabic.dir = 'rtl';
            const ratio = [0.25, 0.5, 0.75, 1][Math.min(3, c.reps || 0)];
            renderMasked(arabic, c.arabic, ratio, c.id);
            card.appendChild(arabic);

            const reveal = i18nEl('button', 'lc-btn', 'pg_lc_mem_reveal');
            reveal.type = 'button';
            const answer = el('div', 'lc-answer');
            answer.hidden = true;
            reveal.addEventListener('click', () => {
                renderMasked(arabic, c.arabic, 0, c.id);
                answer.hidden = false;
                reveal.hidden = true;
            });

            answer.appendChild(el('p', 'lc-text', c.swahili));
            const ref = el('a', 'lc-link', c.ref); ref.href = c.url;
            answer.appendChild(ref);
            const grades = el('div', 'lc-grades');
            [['pg_lc_g0', 0], ['pg_lc_g1', 1], ['pg_lc_g2', 2], ['pg_lc_g3', 3]].forEach(([key, g]) => {
                const b = i18nEl('button', 'lc-btn lc-grade lc-grade-' + g, key);
                b.type = 'button';
                b.addEventListener('click', async () => {
                    b.disabled = true;
                    try { await post(root.dataset.review + '/' + c.id + '/review', { grade: g }); } catch (_) {}
                    i++; show();
                });
                grades.appendChild(b);
            });
            answer.appendChild(grades);
            card.append(reveal, answer);
            root.appendChild(card);
            applyI18n(root);
        };

        fetch(root.dataset.url, { headers: { 'Accept': 'application/json' } })
            .then((r) => r.json())
            .then((d) => { cards = d.cards || []; cards.length ? show() : done(); })
            .catch(done);
    }

    document.addEventListener('DOMContentLoaded', () => {
        initPractice();
        initToggleButtons();
        initQuiz();
        initMemorize();
    });
})();
