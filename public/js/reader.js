(() => {
    const languages = ['both', 'sw', 'en', 'ar'];
    const selector = document.getElementById('reading-language');
    let preferred = 'both';
    try { preferred = localStorage.getItem('hadith-language') || 'both'; } catch (_) {}
    if (!languages.includes(preferred)) preferred = 'both';
    const setLanguage = value => {
        document.documentElement.dataset.readingLanguage = value;
        selector.value = value;
        try { localStorage.setItem('hadith-language', value); } catch (_) {}
    };
    setLanguage(preferred);
    selector.addEventListener('change', () => setLanguage(selector.value));

    // Bookmarks: hadith ids kept on-device only (localStorage), read by the /vipendwa page.
    const bookmarkBtn = document.getElementById('bookmark-toggle');
    if (bookmarkBtn) {
        const id = bookmarkBtn.dataset.hadithId;
        const readBookmarks = () => { try { return JSON.parse(localStorage.getItem('hadith-bookmarks')) || []; } catch (_) { return []; } };
        const writeBookmarks = list => { try { localStorage.setItem('hadith-bookmarks', JSON.stringify(list)); } catch (_) {} };
        const syncBookmark = () => {
            const saved = readBookmarks().includes(id);
            bookmarkBtn.setAttribute('aria-pressed', saved ? 'true' : 'false');
            bookmarkBtn.textContent = saved ? '★ Imehifadhiwa kwenye Vipendwa' : '☆ Hifadhi kwenye Vipendwa';
        };
        bookmarkBtn.addEventListener('click', () => {
            const list = readBookmarks();
            const next = list.includes(id) ? list.filter(saved => saved !== id) : [...list, id];
            writeBookmarks(next);
            syncBookmark();
        });
        syncBookmark();
    }

    // Copy/share: Web Share API when available, clipboard copy otherwise.
    const shareBtn = document.getElementById('share-hadith');
    if (shareBtn) {
        const defaultLabel = shareBtn.textContent;
        shareBtn.addEventListener('click', async () => {
            const text = shareBtn.dataset.shareText || document.title;
            const url = shareBtn.dataset.shareUrl || location.href;
            if (navigator.share) {
                try { await navigator.share({ text, url }); return; } catch (_) { /* cancelled or unsupported; fall back to copy */ }
            }
            try {
                await navigator.clipboard.writeText(`${text}\n${url}`);
                shareBtn.textContent = 'Imenakiliwa ✓';
            } catch (_) {
                shareBtn.textContent = 'Imeshindwa kunakili';
            }
            setTimeout(() => { shareBtn.textContent = defaultLabel; }, 2500);
        });
    }

    const play = document.getElementById('audio-play');
    if (!play) return;
    const status = document.getElementById('audio-status');
    if (!('speechSynthesis' in window) || !('SpeechSynthesisUtterance' in window)) {
        status.textContent = 'Kivinjari hiki hakina huduma ya kusoma kwa sauti. Jaribu kivinjari au kifaa kingine.';
        return;
    }
    const synth = window.speechSynthesis;
    const language = document.getElementById('audio-language');
    const voices = document.getElementById('audio-voice');
    const rate = document.getElementById('audio-rate');
    const pause = document.getElementById('audio-pause');
    const stop = document.getElementById('audio-stop');
    let available = [], generation = 0, active = false, paused = false, utterance;

    // Remembered audio preferences (language, voice, speed) persist across hadith pages/sessions.
    let audioPrefs = {};
    try { audioPrefs = JSON.parse(localStorage.getItem('hadith-audio')) || {}; } catch (_) {}
    const saveAudioPrefs = () => { try { localStorage.setItem('hadith-audio', JSON.stringify(audioPrefs)); } catch (_) {} };

    if (audioPrefs.language && document.getElementById(`text-${audioPrefs.language}`)) {
        language.value = audioPrefs.language;
    } else if (preferred !== 'both' && document.getElementById(`text-${preferred}`)) {
        language.value = preferred;
    }
    if (audioPrefs.rate && [...rate.options].some(option => option.value === audioPrefs.rate)) {
        rate.value = audioPrefs.rate;
    }

    const controls = () => {
        play.disabled = active || !available.length;
        pause.disabled = stop.disabled = !active;
        pause.textContent = paused ? '▶ Endelea' : 'Ⅱ Sitisha kwa muda';
    };
    const cancel = () => {
        generation++;
        active = paused = false;
        synth.cancel();
        controls();
    };
    const loadVoices = () => {
        if (active) cancel();
        available = synth.getVoices().filter(v => v.lang.toLowerCase().split(/[-_]/)[0] === language.value);
        voices.replaceChildren();
        available.forEach((voice, index) => voices.add(new Option(`${voice.name} (${voice.lang})`, String(index))));
        if (!available.length) voices.add(new Option('Sauti haipatikani', ''));
        voices.disabled = !available.length;
        if (audioPrefs.voiceName) {
            const match = available.findIndex(v => v.name === audioPrefs.voiceName && v.lang === audioPrefs.voiceLang);
            if (match !== -1) voices.value = String(match);
        }
        status.textContent = available.length ? 'Tayari. Bonyeza “Soma kwa sauti”.' : 'Kifaa hiki hakina sauti ya lugha hii. Sakinisha sauti ya lugha hii kwenye mipangilio ya kifaa, au chagua lugha nyingine.';
        controls();
    };
    play.addEventListener('click', () => {
        const voice = available[Number(voices.value)];
        const text = document.getElementById(`text-${language.value}`)?.textContent.trim();
        if (!voice || !text) return;
        cancel();
        const token = generation;
        // Short, word-aligned chunks avoid truncation on engines with utterance limits.
        const chunks = text.match(/.{1,180}(?:\s|$)|\S{1,180}/gu) || [];
        let index = 0;
        active = true;
        controls();
        const next = () => {
            if (token !== generation) return;
            if (index === chunks.length) {
                active = paused = false;
                controls();
                status.textContent = 'Usomaji umekamilika.';
                return;
            }
            utterance = new SpeechSynthesisUtterance(chunks[index++]);
            utterance.voice = voice;
            utterance.lang = voice.lang;
            utterance.rate = Number(rate.value);
            utterance.onend = next;
            utterance.onerror = event => {
                if (token !== generation) return;
                cancel();
                status.textContent = 'Sauti imeshindwa kusoma. Jaribu tena au chagua sauti nyingine.';
            };
            status.textContent = `Inasoma… ${index} / ${chunks.length}`;
            synth.speak(utterance);
        };
        next();
    });
    pause.addEventListener('click', () => {
        if (!active) return;
        paused = !paused;
        if (paused) synth.pause(); else synth.resume();
        status.textContent = paused ? 'Imesitishwa kwa muda.' : 'Inaendelea kusoma…';
        controls();
    });
    stop.addEventListener('click', () => { cancel(); status.textContent = 'Usomaji umeachwa.'; });
    language.addEventListener('change', () => {
        audioPrefs.language = language.value;
        saveAudioPrefs();
        cancel();
        loadVoices();
    });
    voices.addEventListener('change', () => {
        const voice = available[Number(voices.value)];
        if (voice) {
            audioPrefs.voiceName = voice.name;
            audioPrefs.voiceLang = voice.lang;
            saveAudioPrefs();
        }
        cancel();
        status.textContent = 'Mipangilio imebadilishwa. Bonyeza kusoma upya.';
    });
    rate.addEventListener('change', () => {
        audioPrefs.rate = rate.value;
        saveAudioPrefs();
        cancel();
        status.textContent = 'Mipangilio imebadilishwa. Bonyeza kusoma upya.';
    });
    synth.addEventListener('voiceschanged', loadVoices);
    window.addEventListener('pagehide', cancel);
    document.addEventListener('livewire:navigating', cancel);
    loadVoices();
})();
