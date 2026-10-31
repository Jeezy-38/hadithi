/*
 * Shiriki hadith kama picha: hutengeneza kadi (PNG) kwa canvas ndani ya kivinjari,
 * kisha hufungua menyu ya kushiriki ya simu au kupakua picha.
 */
(() => {
    'use strict';

    const W = 1080, H = 1350;
    const GOLD = '#e0c868', TEXT = '#eef6fb', MUTED = '#c3d7e6';
    const T = (k) => (window.BaytI18n ? window.BaytI18n.t(k) : k);

    const loadImage = (src) => new Promise((resolve) => {
        const img = new Image();
        img.onload = () => resolve(img);
        img.onerror = () => resolve(null);
        img.src = src;
    });

    function wrap(ctx, text, maxWidth) {
        const words = text.split(/\s+/).filter(Boolean);
        const lines = [];
        let line = '';
        words.forEach((w) => {
            const test = line ? line + ' ' + w : w;
            if (ctx.measureText(test).width > maxWidth && line) {
                lines.push(line);
                line = w;
            } else {
                line = test;
            }
        });
        if (line) lines.push(line);
        return lines;
    }

    /** Hupunguza ukubwa wa herufi hadi maandishi yatoshe ndani ya mistari iliyoruhusiwa. */
    function fit(ctx, text, family, weight, start, min, maxWidth, maxLines) {
        let size = start;
        for (; size >= min; size -= 2) {
            ctx.font = `${weight} ${size}px ${family}`;
            const lines = wrap(ctx, text, maxWidth);
            if (lines.length <= maxLines) return { size, lines };
        }
        ctx.font = `${weight} ${min}px ${family}`;
        let lines = wrap(ctx, text, maxWidth);
        if (lines.length > maxLines) {
            lines = lines.slice(0, maxLines);
            let last = lines[maxLines - 1];
            while (last.length > 1 && ctx.measureText(last + ' …').width > maxWidth) last = last.slice(0, -1);
            lines[maxLines - 1] = last.trimEnd() + ' …';
        }
        return { size: min, lines };
    }

    async function render(d) {
        try { await Promise.all([
            document.fonts.load('56px Amiri'), document.fonts.load('700 56px Amiri'),
            document.fonts.load('600 34px "Plus Jakarta Sans"'), document.fonts.load('700 30px "Plus Jakarta Sans"'),
        ]); } catch (_) {}
        const logo = await loadImage(d.logo);

        const canvas = document.createElement('canvas');
        canvas.width = W; canvas.height = H;
        const ctx = canvas.getContext('2d');

        const bg = ctx.createLinearGradient(0, 0, 0, H);
        bg.addColorStop(0, '#0a3552'); bg.addColorStop(1, '#07293f');
        ctx.fillStyle = bg; ctx.fillRect(0, 0, W, H);

        ctx.strokeStyle = GOLD; ctx.globalAlpha = 0.9; ctx.lineWidth = 3;
        ctx.strokeRect(36, 36, W - 72, H - 72);
        ctx.globalAlpha = 0.35; ctx.lineWidth = 1.5;
        ctx.strokeRect(54, 54, W - 108, H - 108);
        ctx.globalAlpha = 1;

        let y = 92;
        if (logo) {
            const lh = 130, lw = logo.width / logo.height * lh;
            ctx.drawImage(logo, (W - lw) / 2, y, lw, lh);
            y += lh + 18;
        }
        ctx.textAlign = 'center'; ctx.direction = 'ltr'; ctx.textBaseline = 'alphabetic';
        ctx.fillStyle = GOLD;
        ctx.font = '700 30px "Plus Jakarta Sans", system-ui, sans-serif';
        if ('letterSpacing' in ctx) ctx.letterSpacing = '6px';
        ctx.fillText('BAYT UL HADITH', W / 2, y + 26);
        if ('letterSpacing' in ctx) ctx.letterSpacing = '0px';
        y += 70;
        ctx.fillText('✦', W / 2, y); y += 30;

        const innerW = W - 200;
        const lang = document.documentElement.dataset.readingLanguage || 'both';
        const translation = lang === 'en' && d.en ? d.en : d.sw;
        const showTranslation = lang !== 'ar' && translation;

        // Kiarabu
        ctx.direction = 'rtl'; ctx.textAlign = 'center'; ctx.fillStyle = TEXT;
        const arLines = showTranslation ? 6 : 12;
        const ar = fit(ctx, d.ar, 'Amiri, "Noto Naskh Arabic", serif', '400', showTranslation ? 56 : 66, 36, innerW, arLines);
        const arLH = ar.size * 1.75;
        y += 20;
        ar.lines.forEach((l, i) => ctx.fillText(l, W / 2, y + ar.size + i * arLH));
        y += ar.lines.length * arLH + 36;

        // Tafsiri
        ctx.direction = 'ltr';
        if (showTranslation) {
            ctx.strokeStyle = GOLD; ctx.globalAlpha = 0.5; ctx.lineWidth = 2;
            ctx.beginPath(); ctx.moveTo(W / 2 - 60, y - 10); ctx.lineTo(W / 2 + 60, y - 10); ctx.stroke(); ctx.globalAlpha = 1;
            const bottomLimit = H - 250;
            const maxLines = Math.max(3, Math.floor((bottomLimit - y) / 50));
            const tr = fit(ctx, '“' + translation + '”', '"Plus Jakarta Sans", system-ui, sans-serif', '500', 36, 26, innerW, maxLines);
            ctx.fillStyle = MUTED;
            const lh = tr.size * 1.5;
            tr.lines.forEach((l, i) => ctx.fillText(l, W / 2, y + 20 + tr.size + i * lh));
        }

        // Rejea na kiungo
        ctx.fillStyle = GOLD;
        ctx.font = '700 38px "Plus Jakarta Sans", system-ui, sans-serif';
        ctx.fillText(d.ref, W / 2, H - 175);
        ctx.fillStyle = MUTED;
        ctx.font = '500 26px "Plus Jakarta Sans", system-ui, sans-serif';
        const sub = fit(ctx, d.sub, '"Plus Jakarta Sans", system-ui, sans-serif', '500', 26, 20, innerW, 1);
        ctx.fillText(sub.lines[0], W / 2, H - 130);
        ctx.fillStyle = GOLD;
        ctx.font = '600 26px "Plus Jakarta Sans", system-ui, sans-serif';
        ctx.fillText(d.host, W / 2, H - 84);

        return new Promise((resolve) => canvas.toBlob(resolve, 'image/png'));
    }

    async function onClick(btn) {
        const label = btn.querySelector('.btn-text');
        const original = label ? label.textContent : '';
        btn.disabled = true;
        if (label) label.textContent = T('pg_sh_busy');
        try {
            const blob = await render({
                ar: btn.dataset.arabic || '', sw: btn.dataset.sw || '', en: btn.dataset.en || '',
                ref: btn.dataset.ref || '', sub: btn.dataset.sub || '', logo: btn.dataset.logo,
                host: (btn.dataset.url || location.href).replace(/^https?:\/\//, ''),
            });
            if (!blob) throw new Error('empty');
            const file = new File([blob], `hadith-${btn.dataset.number || 'bayt-ul-hadith'}.png`, { type: 'image/png' });
            if (navigator.canShare && navigator.canShare({ files: [file] })) {
                await navigator.share({ files: [file], title: btn.dataset.ref || 'Bayt Ul Hadith', text: btn.dataset.url || location.href });
            } else {
                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = file.name;
                document.body.appendChild(a); a.click(); a.remove();
                setTimeout(() => URL.revokeObjectURL(a.href), 4000);
            }
        } catch (e) {
            if (e && e.name !== 'AbortError') window.alert(T('pg_sh_fail'));
        } finally {
            btn.disabled = false;
            if (label) label.textContent = original;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-share-image]').forEach((btn) => btn.addEventListener('click', () => onClick(btn)));
    });
})();
