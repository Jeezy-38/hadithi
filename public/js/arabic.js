(function () {
  var app = document.getElementById('ar-app');
  if (!app) return;
  var panel = document.getElementById('ar-panel');
  var endpoint = app.getAttribute('data-endpoint');
  var active = null;
  function clean(w) { return w.replace(/[^؀-ۿ]/g, ''); }
  function t(k, d) { return (window.BaytI18n && BaytI18n.t && BaytI18n.t(k)) || d; }
  function esc(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; }
  app.addEventListener('click', function (e) {
    var b = e.target.closest('.ar-word');
    if (!b) return;
    if (active) active.classList.remove('is-active');
    active = b; b.classList.add('is-active');
    panel.innerHTML = '<p class="ar-hint">…</p>';
    fetch(endpoint + '?w=' + encodeURIComponent(clean(b.getAttribute('data-w'))), { headers: { Accept: 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (d) {
        var h = '<div class="ar-head"><span class="ar-big" dir="rtl" lang="ar">' + esc(d.word) + '</span><span class="ar-count">' + d.total + ' ' + esc(t('pg_ar_found', 'hadith')) + '</span></div>';
        var a = d.analysis, posMap = { noun: 'pg_ar_pos_noun', verb: 'pg_ar_pos_verb', particle: 'pg_ar_pos_part', stopword: 'pg_ar_pos_part' };
        if (a) {
          var pos = a.pos ? t(posMap[a.pos] || '', a.pos) : '—';
          h += '<dl class="ar-detail">' +
            '<dt>' + esc(t('pg_ar_d_word', 'Neno')) + '</dt><dd dir="rtl" lang="ar">' + esc(d.word) + '</dd>' +
            '<dt>' + esc(t('pg_ar_d_root', 'Mzizi')) + '</dt><dd dir="rtl" lang="ar">' + esc(a.root || '—') + '</dd>' +
            '<dt>' + esc(t('pg_ar_d_lemma', 'Umbo la msingi')) + '</dt><dd dir="rtl" lang="ar">' + esc(a.lemma || '—') + '</dd>' +
            '<dt>' + esc(t('pg_ar_d_pos', 'Aina ya neno')) + '</dt><dd>' + esc(pos) + '</dd>' +
            '<dt>' + esc(t('pg_ar_d_lang', 'Lugha')) + '</dt><dd>' + esc(t('pg_ar_d_lang_v', 'Kiarabu')) + '</dd>' +
            '</dl><p class="ar-note">' + esc(t('pg_ar_d_auto', 'Mzizi na aina ni makadirio ya kiotomatiki; maana na tafsiri bado.')) + '</p>';
        }
        if (!d.items.length) h += '<p class="ar-hint">' + esc(t('pg_rs_none', 'Hakuna matokeo.')) + '</p>';
        else h += '<ul class="ar-list">' + d.items.map(function (i) {
          return '<li><a href="' + esc(i.url) + '"><b>' + esc(i.ref) + '</b> — ' + esc(i.title) + '</a></li>';
        }).join('') + '</ul>';
        panel.innerHTML = h;
      })
      .catch(function () { panel.innerHTML = '<p class="ar-hint">' + esc(t('pg_sh_fail', 'Imeshindikana.')) + '</p>'; });
  });
})();
