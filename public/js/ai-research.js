(function () {
  var app = document.getElementById('ai-app'); if (!app) return;
  var form = document.getElementById('ai-form'), q = document.getElementById('ai-q'), out = document.getElementById('ai-out');
  var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
  function t(k, d) { return (window.BaytI18n && BaytI18n.t && BaytI18n.t(k)) || d; }
  function esc(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; }
  var busy = false;
  form.addEventListener('submit', function (e) {
    e.preventDefault(); if (busy) return; busy = true;
    out.innerHTML = '<p class="ar-hint">…</p>';
    fetch(app.getAttribute('data-endpoint'), {
      method: 'POST', headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify({ q: q.value })
    }).then(function (r) { return r.json().then(function (d) { return { s: r.status, d: d }; }); })
      .then(function (x) {
        var d = x.d;
        if (x.s === 429) { out.innerHTML = '<p class="ar-hint">' + esc(t('pg_ai_limit', 'Umefikia kikomo cha maswali ya leo. Rudi kesho.')) + '</p>'; return; }
        if (x.s >= 400) { out.innerHTML = '<p class="ar-hint">' + esc(t('pg_sh_fail', 'Imeshindikana. Jaribu tena.')) + '</p>'; return; }
        if (d.none) { out.innerHTML = '<p class="ar-hint">' + esc(t('pg_ai_none', 'Sikupata hadith zinazohusiana na swali hili.')) + '</p>'; return; }
        var h = '<div class="ai-answer">' + esc(d.answer).replace(/\n+/g, '<br>') + '</div><ul class="ar-list">' +
          d.sources.map(function (s) { return '<li><a href="' + esc(s.url) + '"><b>[' + s.n + ']</b> ' + esc(s.ref) + '</a></li>'; }).join('') + '</ul>';
        out.innerHTML = h;
      }).catch(function () { out.innerHTML = '<p class="ar-hint">' + esc(t('pg_sh_fail', 'Imeshindikana. Jaribu tena.')) + '</p>'; })
      .then(function () { busy = false; });
  });
})();
