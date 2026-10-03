<x-layouts.app :title="$hadith->chapter->book->collection->name.' · '.$hadith->number">
    <div class="reader-shell">
        <a class="back-link" href="{{ route('library', ['collection' => $hadith->chapter->book->collection->slug, 'book' => $hadith->chapter->book_id, 'chapter' => $hadith->chapter_id]) }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span>Rudi kwenye mlango</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> {{ $hadith->chapter->book->collection->name }}</span>
            <h1>Hadith {{ $hadith->number }}</h1>
            @if($hadith->title)
                <h2 class="reader-title">{{ $hadith->title }}</h2>
            @endif
            <p class="reader-subtitle">{{ $hadith->chapter->book->title_sw }} · {{ $hadith->chapter->title_sw }}</p>
        </div>

        <div class="reader-actions no-print">
            <button type="button" id="bookmark-toggle" class="text-button action-pill-btn" data-hadith-id="{{ $hadith->id }}" aria-pressed="false">
                <span class="btn-icon" aria-hidden="true">☆</span>
                <span class="btn-text">Hifadhi kwenye Vipendwa</span>
            </button>
            <button type="button" id="share-hadith" class="text-button action-pill-btn" data-share-text="{{ $hadith->chapter->book->collection->name.' '.$hadith->number.': '.\Illuminate\Support\Str::limit($hadith->swahili, 200) }}" data-share-url="{{ route('hadith.show', $hadith) }}">
                <span class="btn-icon" aria-hidden="true">⧉</span>
                <span class="btn-text">Nakili / Shiriki</span>
            </button>
            <a class="text-button action-pill-btn bookmarks-link" href="{{ route('bookmarks') }}">
                <span>Vipendwa vyangu</span>
                <span aria-hidden="true">→</span>
            </a>
        </div>

        <article class="reader-card">
            <section class="audio-player no-print" aria-label="Kusikiliza hadith" data-hadith-id="{{ $hadith->id }}" data-audio-base="{{ url('/audio/hadith/'.$hadith->id) }}"
                @if($next) data-next-url="{{ route('hadith.show', $next) }}" data-next-audio-base="{{ url('/audio/hadith/'.$next->id) }}" @endif>
                <div class="audio-header">
                    <div class="audio-header-title">
                        <span class="audio-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle;">
                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                            </svg>
                        </span>
                        <h2>Sikiliza hadith</h2>
                    </div>
                    <span class="audio-badge badge-hd" id="audio-badge">Sauti Fasaha (HD)</span>
                </div>
                <p class="audio-desc">Sikiliza hadith ikisomwa kwa matamshi fasaha na ya asili katika Kiswahili, Kiarabu au Kiingereza.</p>
                <div class="audio-options">
                    <label>
                        <span>Lugha ya sauti</span>
                        <div class="select-wrapper">
                            <select id="audio-language">
                                <option value="sw">Kiswahili</option>
                                <option value="ar">العربية (Kiarabu)</option>
                                <option value="en" @disabled(!$hadith->english)>English</option>
                            </select>
                        </div>
                    </label>
                    <label>
                        <span>Msomaji</span>
                        <div class="select-wrapper">
                            <select id="audio-voice" aria-describedby="audio-status">
                                <option value="hd-natural" selected>Kiume · HD</option>
                            </select>
                        </div>
                    </label>
                    <label>
                        <span>Kasi ya kusoma</span>
                        <div class="select-wrapper">
                            <select id="audio-rate">
                                <option value="0.75">0.75×</option>
                                <option value="0.9">0.9×</option>
                                <option value="1" selected>1×</option>
                                <option value="1.15">1.15×</option>
                                <option value="1.25">1.25×</option>
                                <option value="1.5">1.5×</option>
                            </select>
                        </div>
                    </label>
                    <label>
                        <span>Mfululizo</span>
                        <div class="select-wrapper">
                            <select id="audio-sequence" title="Soma Kiarabu kisha tafsiri">
                                <option value="single" selected>Lugha moja</option>
                                <option value="ar-sw">Ar → Sw</option>
                                <option value="ar-en" @disabled(!$hadith->english)>Ar → En</option>
                            </select>
                        </div>
                    </label>
                </div>

                <div class="audio-progress-container" id="audio-progress-container">
                    <div class="audio-time-row">
                        <span id="audio-current-time" class="audio-time">0:00</span>
                        <div class="audio-track-wrap">
                            <div class="audio-track-bg">
                                <div class="audio-progress-fill" id="audio-progress-fill"></div>
                            </div>
                            <div class="audio-thumb-indicator" id="audio-thumb-indicator" style="left: 0%;"></div>
                            <input type="range" id="audio-scrubber" class="audio-scrubber" min="0" max="100" value="0" step="0.1" aria-label="Muda wa sauti" disabled>
                        </div>
                        <span id="audio-total-time" class="audio-time">0:00</span>
                    </div>
                </div>

                <div class="audio-actions">
                    <button type="button" id="audio-play" class="audio-btn play-btn">
                        <span aria-hidden="true">▶</span><span class="btn-audio-label">Soma kwa sauti</span>
                    </button>
                    @if(trim((string) $hadith->arabic) !== '' && trim((string) $hadith->swahili) !== '')
                        <button type="button" id="audio-play-both" class="audio-btn both-btn" title="Soma Kiarabu kisha Kiswahili">
                            <span aria-hidden="true">▶▶</span><span class="btn-audio-label">Kiarabu + Kiswahili</span>
                        </button>
                    @endif
                    <button type="button" id="audio-pause" class="audio-btn pause-btn" disabled>
                        <span aria-hidden="true">Ⅱ</span><span class="btn-audio-label">Sitisha kwa muda</span>
                    </button>
                    <button type="button" id="audio-stop" class="audio-btn stop-btn" disabled>
                        <span aria-hidden="true">■</span><span class="btn-audio-label">Acha</span>
                    </button>
                    @if($next)
                        <label class="audio-autonext" title="Hadith inayofuata: Na. {{ $next->number }}">
                            <input type="checkbox" id="audio-autonext">
                            <span>Endelea na hadith inayofuata</span>
                        </label>
                    @endif
                </div>
                <audio id="hadith-native-audio" preload="metadata" style="display:none;"></audio>
                <p id="audio-status" class="audio-status-text" role="status" aria-live="polite">Tayari kusoma kwa sauti fasaha ya HD. Bonyeza “Soma kwa sauti”.</p>
                <noscript>Washa JavaScript ili kutumia sauti na kubadilisha lugha.</noscript>
            </section>

            <section data-reading="ar" class="reading-section arabic-section">
                <div class="section-label-bar">
                    <span class="section-label">MAANDISHI YA KIARABU · {{ $hadith->source_name }}</span>
                    <span class="arabic-lang-tag">العربية</span>
                </div>
                <div class="manuscript-box">
                    <p id="text-ar" class="arabic full-text reading-arabic" lang="ar" dir="rtl">{{ $hadith->arabic }}</p>
                </div>
            </section>

            <section data-reading="sw" class="reading-section swahili-section">
                <div class="section-label-bar">
                    <span class="section-label">TAFSIRI YA KISWAHILI</span>
                    <span class="lang-pill">Kiswahili</span>
                </div>
                <p id="text-sw" class="full-text swahili reading-swahili" lang="sw">{{ $hadith->swahili }}</p>
            </section>

            <section data-reading="en" class="reading-section english-section">
                <div class="section-label-bar">
                    <span class="section-label">ENGLISH TRANSLATION</span>
                    <span class="lang-pill">English</span>
                </div>
                @if($hadith->english)
                    <p id="text-en" class="full-text reading-english" lang="en">{{ $hadith->english }}</p>
                    <p class="source-note">Translation: <a href="https://hadeethenc.com/en/browse/hadith/{{ $hadith->source_record_id }}" target="_blank" rel="noopener noreferrer">HadeethEnc.com ↗</a></p>
                @else
                    <p class="not-available-note">Tafsiri ya Kiingereza bado haipatikani.</p>
                @endif
            </section>

            @if($hadith->grade)
                <div class="hadith-grade-card">
                    <span class="grade-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                    </span>
                    <p class="source-note">Daraja kwa mujibu wa {{ $hadith->source_name }}: <strong>{{ $hadith->grade }}</strong> · {{ $hadith->attribution }}</p>
                </div>
            @endif
        </article>

        <section class="source-card" aria-labelledby="source-heading">
            <div class="source-card-header">
                <span class="source-icon">◈</span>
                <h2 id="source-heading">Rejea na chanzo</h2>
            </div>
            <dl class="source-meta-grid">
                <div class="source-item"><dt>Rejea</dt><dd class="source-val-bold">{{ $hadith->reference }}</dd></div>
                <div class="source-item"><dt>Kitabu / mlango</dt><dd>{{ $hadith->chapter->book->number }} / {{ $hadith->chapter->number }}</dd></div>
                <div class="source-item"><dt>Mfumo wa namba</dt><dd>{{ $hadith->numbering_system }}</dd></div>
                @if($hadith->reference_url)
                    <div class="source-item"><dt>Linganisha rejea</dt><dd><a href="{{ $hadith->reference_url }}" target="_blank" rel="noopener noreferrer">{{ $hadith->chapter->book->collection->name }} {{ $hadith->number }} · Sunnah.com ↗</a></dd></div>
                @endif
                <div class="source-item"><dt>Chanzo cha Kiarabu</dt><dd><a href="{{ $hadith->source_url }}" target="_blank" rel="noopener noreferrer">{{ $hadith->source_name }}{{ $hadith->source_record_id ? ' #'.$hadith->source_record_id : '' }} ↗</a></dd></div>
                <div class="source-item"><dt>Tafsiri ya Kiswahili</dt><dd><a href="{{ $hadith->translation_source_url }}" target="_blank" rel="noopener noreferrer">{{ $hadith->translator }} ↗</a></dd></div>
                <div class="source-item"><dt>Ruhusa ya matumizi</dt><dd>{{ $hadith->license }} @if($hadith->license_url)<a href="{{ $hadith->license_url }}" target="_blank" rel="noopener noreferrer">Masharti ↗</a>@endif</dd></div>
                @if($hadith->source_fetched_at)
                    <div class="source-item"><dt>Ilipakuliwa</dt><dd>{{ $hadith->source_fetched_at->format('d/m/Y') }}</dd></div>
                @endif
                <div class="source-item"><dt>Ulinganisho uliorekodiwa</dt><dd>{{ $hadith->reviewed_by }} · {{ $hadith->reviewed_at->format('d/m/Y') }}</dd></div>
            </dl>
            @if($hadith->content_note)
                <p class="source-note source-note-box">{{ $hadith->content_note }}</p>
            @endif
        </section>

        @if($related->isNotEmpty())
            <section class="related-hadith no-print" aria-label="Hadith nyingine za mlango huu">
                <div class="related-header">
                    <h2>Hadith nyingine za mlango huu</h2>
                    <span class="related-count">{{ $related->count() }} hadith</span>
                </div>
                <ul class="related-list">
                    @foreach($related as $item)
                        <li class="related-item">
                            <a href="{{ route('hadith.show', $item) }}" class="related-link">
                                <span class="related-num">Na. {{ $item->number }}</span>
                                <span class="related-text">{{ \Illuminate\Support\Str::limit($item->swahili, 90) }}</span>
                                <span class="related-arrow" aria-hidden="true">→</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
</x-layouts.app>
