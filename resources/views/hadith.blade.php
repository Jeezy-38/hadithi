@php
    $seoCol = $hadith->chapter->book->collection->name;
    $seoText = trim(preg_replace('/\s+/u', ' ', (string) $hadith->display_text));
    $seoHead = \Illuminate\Support\Str::limit($seoText, 70, '…');
    $seoTitle = $seoCol.' Na. '.$hadith->number.($seoHead !== '' ? ' — '.$seoHead : '');
    $seoDescription = $seoCol.' Na. '.$hadith->number.': '.\Illuminate\Support\Str::limit($seoText, 150, '…');
    $seoLd = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => \Illuminate\Support\Str::limit($seoTitle, 110, '…'),
        'inLanguage' => $hadith->has_swahili ? 'sw' : 'en',
        'url' => route('hadith.show', $hadith),
        'description' => $seoDescription,
        'isPartOf' => ['@type' => 'WebSite', 'name' => 'Bayt Ul Hadith', 'url' => url('/')],
        'publisher' => ['@type' => 'Organization', 'name' => 'Bayt Ul Hadith', 'logo' => ['@type' => 'ImageObject', 'url' => url('images/logo.png')]],
        'about' => $hadith->chapter->book->title_sw.' · '.$hadith->chapter->title_sw,
    ];
@endphp
<x-layouts.app :title="$seoTitle" :description="$seoDescription" og-type="article" :canonical="route('hadith.show', $hadith)" :noindex="! $hadith->has_swahili" :json-ld="$seoLd">
    <div class="reader-shell hadith-reader-shell dua-detail-shell hadith-detail-shell" data-hadith-scope>
        @php
            $shareArabic = trim((string) $hadith->arabic);
            $shareArabicShort = \Illuminate\Support\Str::limit($shareArabic, 320);
            $shareSwahiliShort = \Illuminate\Support\Str::limit($hadith->display_text, 420);
            $shareTitle = "Hadith " . $hadith->number;
            $shareBadge = $hadith->chapter->book->collection->name;
            $bookSw = $hadith->chapter->book->titleFor('sw');
            $bookEn = $hadith->chapter->book->titleFor('en');
            $shareRef = $hadith->chapter->book->collection->name . " Na. " . $hadith->number . " (" . $bookSw . " · " . $hadith->chapter->title_sw . ")";
            $shareEnglishShort = \Illuminate\Support\Str::limit((string) ($hadith->english ?: $hadith->display_text), 420);
            $shareRefEn = $hadith->chapter->book->collection->name . " No. " . $hadith->number . " (" . $bookEn . " · " . $hadith->chapter->titleFor('en') . ")";
            $shareFormattedEn = "Hadith: " . $shareBadge . " No. " . $hadith->number . "\n\n"
                . ($shareArabicShort ? $shareArabicShort . "\n\n" : "")
                . "“" . $shareEnglishShort . "”\n\n"
                . "Reference: " . $shareRefEn;
            $shareFormatted = "Hadithi: " . $shareBadge . " Na. " . $hadith->number . "\n\n"
                . ($shareArabicShort ? $shareArabicShort . "\n\n" : "")
                . "“" . $shareSwahiliShort . "”\n\n"
                . "Rejea: " . $shareRef;
            $shareFullText = $shareFormatted . "\n\nSoma zaidi: " . route('hadith.show', $hadith);
            $whatsappUrl = 'https://api.whatsapp.com/send?text=' . rawurlencode($shareFullText);
        @endphp

        <div class="reader-top-nav">
            <a class="back-link" href="{{ route('library', ['collection' => $hadith->chapter->book->collection->slug, 'book' => $hadith->chapter->book_id, 'chapter' => $hadith->chapter_id]) }}">
                <span class="back-arrow" aria-hidden="true">←</span>
                <span data-i18n="pg_lc_back">Nyuma</span>
            </a>

            <div class="reader-actions no-print">
                <button type="button" id="top-btn-hadith-chat" class="reader-ai-chat-btn-top" title="Uliza ufafanuzi kuhusu hadithi hii" aria-label="Uliza Ufafanuzi">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span class="btn-text">Uliza Ufafanuzi</span>
                </button>
                <div class="reader-more-menu-wrap">
                    <button type="button" id="reader-more-btn" class="reader-more-toggle" aria-expanded="false" aria-haspopup="true" title="Chaguzi zaidi" data-i18n-title="pg_h_more">
                        <span class="more-dots" aria-hidden="true">⋯</span>
                        <span class="sr-only" data-i18n="pg_h_more">Chaguzi zaidi</span>
                    </button>
                    <div id="reader-more-dropdown" class="reader-more-dropdown" hidden>
                        {{-- 1. WhatsApp Moja kwa Moja --}}
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="more-dropdown-item" id="reader-whatsapp-direct" title="Tuma moja kwa moja WhatsApp">
                            <span class="dropdown-item-icon whatsapp-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.23 8.23 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24m4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.4-1.75-.15-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.12-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.78 2.71 4.3 3.8.6.26 1.07.42 1.44.54.61.19 1.16.17 1.6.1.49-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/>
                                </svg>
                            </span>
                            <span class="btn-text">WhatsApp</span>
                        </a>

                        {{-- 2. Nakili Matini --}}
                        <button type="button" id="reader-copy-direct" class="more-dropdown-item" title="Nakili hadithi yote">
                            <span class="dropdown-item-icon" id="reader-copy-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                            </span>
                            <span class="btn-text" id="reader-copy-label">Nakili Matini</span>
                        </button>

                        {{-- 3. Kushiriki Mitandao Mingine (Native Share) --}}
                        <button type="button" id="share-hadith" class="more-dropdown-item share-trigger-btn"
                            data-share-title="{{ $shareTitle }}"
                            data-share-badge="{{ $shareBadge }}"
                            data-share-ar="{{ $shareArabicShort }}"
                            data-share-sw="{{ $shareSwahiliShort }}"
                            data-share-ref="{{ $shareRef }}"
                            data-share-text="{{ $shareFormatted }}"
                            data-share-text-en="{{ $shareFormattedEn }}"
                            data-share-url="{{ route('hadith.show', $hadith) }}"
                            title="Shiriki kwenye mitandao mingine">
                            <span class="dropdown-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="18" cy="5" r="3"></circle>
                                    <circle cx="6" cy="12" r="3"></circle>
                                    <circle cx="18" cy="19" r="3"></circle>
                                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                </svg>
                            </span>
                            <span class="btn-text" data-i18n="pg_h_other">Mitandao Mingine</span>
                        </button>

                        {{-- Shiriki kama picha --}}
                        <button type="button" class="more-dropdown-item" data-share-image
                            data-arabic="{{ \Illuminate\Support\Str::limit($shareArabic, 420, '') }}"
                            data-sw="{{ \Illuminate\Support\Str::limit($hadith->display_text, 520) }}"
                            data-en="{{ \Illuminate\Support\Str::limit((string) $hadith->english, 520) }}"
                            data-ref="{{ $hadith->chapter->book->collection->name }} · Na. {{ $hadith->number }}"
                            data-ref-en="{{ $hadith->chapter->book->collection->name }} · No. {{ $hadith->number }}"
                            data-sub="{{ $bookSw }} · {{ $hadith->chapter->title_sw }}"
                            data-sub-en="{{ $bookEn }} · {{ $hadith->chapter->titleFor('en') }}"
                            data-number="{{ $hadith->number }}"
                            data-url="{{ route('hadith.show', $hadith) }}"
                            data-logo="{{ asset('images/logo-gold.png') }}"
                            title="Shiriki kama picha">
                            <span class="dropdown-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                            </span>
                            <span class="btn-text" data-i18n="pg_sh_image">Shiriki kama picha</span>
                        </button>

                        <div class="dropdown-divider" role="separator"></div>

                        {{-- 4. Hifadhi --}}
                        <button type="button" id="bookmark-toggle" class="more-dropdown-item" data-hadith-id="{{ $hadith->id }}" aria-pressed="false">
                            <span class="dropdown-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                            </span>
                            <span class="btn-text" data-i18n="pg_h_save">Hifadhi</span>
                        </button>

                        {{-- 5. Soma kwa sauti (Iliyofichwa kwa ombi la mtumiaji) --}}
                        <button type="button" id="audio-play" class="more-dropdown-item speech-client-btn" title="Sikiliza kwa sauti ya kifaa" style="display: none !important;" aria-hidden="true">
                            <span class="dropdown-item-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                                </svg>
                            </span>
                            <span class="btn-text" data-i18n="pg_h_listen">Soma kwa sauti</span>
                        </button>
                    </div>
                </div>

                <input type="hidden" id="reading-language" value="both">
            </div>
        </div>

        <div class="reader-heading dua-detail-heading">
            @if($hadith->title)
                <h1 class="reader-title">{{ $hadith->title }}</h1>
            @else
                <h1 class="reader-title" data-i18n="pg_h_no" data-i18n-vars='{"n":"{{ $hadith->number }}"}'>Hadith Na. {{ $hadith->number }}</h1>
            @endif
        </div>

        {{-- Arabic Recitation Console (Kisomo cha Kiarabu Pekee) --}}
        @php
            $hasStudio = !empty($hadith->getStudioAudioUrl());
            $arAudioUrl = route('hadith.audio', ['hadith' => $hadith, 'lang' => 'ar']);
        @endphp
        <div class="hadith-reader-audio-bar" id="reader-audio-bar"
             data-ar-audio="{{ $arAudioUrl }}"
             data-has-studio="{{ $hasStudio ? '1' : '0' }}">
            <div class="reader-audio-left">
                <button type="button" class="rawdah-play-btn" id="reader-audio-btn" aria-label="Sikiliza Kisomo cha Kiarabu" title="Sikiliza Kisomo cha Kiarabu">
                    <svg class="icon-play" viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                    <svg class="icon-pause" viewBox="0 0 24 24" width="18" height="18" fill="currentColor" style="display: none;">
                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                    </svg>
                </button>
                <div class="reader-audio-text">
                    <span class="reader-audio-title" id="reader-audio-title">Sikiliza Kisomo</span>
                    <span class="reader-audio-sub" id="reader-audio-sub">{{ $hasStudio ? 'Sheikh · MP3' : 'Kiarabu · MP3' }}</span>
                </div>
            </div>

            <div class="reader-audio-badge-wrap">
                <span class="reader-audio-lang-tag">العربية</span>
            </div>
            <audio id="hadith-reader-audio-element" playsinline preload="none"></audio>
        </div>

        <article class="reader-card dua-detail-card hadith-detail-card">
            {{-- 1. Matini ya Kiarabu --}}
            <section class="reading-section" data-reading="ar" aria-labelledby="label-arabic">
                <div class="section-label-row">
                    <span id="label-arabic" class="section-label" data-i18n="pg_h_l_ar">MAANDISHI YA KIARABU</span>
                    <span class="section-script-badge">العربية بالتشكيل</span>
                </div>
                <div class="arabic-box dua-arabic-box-detail">
                    <p id="text-ar" class="arabic arabic-text font-arabic" lang="ar" dir="rtl">{{ $hadith->arabic }}</p>
                </div>
            </section>

            {{-- 2. Tafsiri ya Kiswahili --}}
            <section class="reading-section" data-reading="sw" @unless($hadith->english) data-no-en @endunless aria-labelledby="label-swahili">
                <div class="section-label-row">
                    @if($hadith->has_swahili)
                        <span id="label-swahili" class="section-label" data-i18n="{{ $hadith->english ? 'pg_h_l_sw' : 'pg_h_l_sw_noen' }}">{{ $hadith->english ? 'TAFSIRI YA KISWAHILI' : 'TAFSIRI YA KISWAHILI (Kiingereza bado hakipo)' }}</span>
                    @else
                        <span id="label-swahili" class="section-label" data-i18n="pg_h_l_en_only">TAFSIRI YA KIINGEREZA (Kiswahili bado hakipo)</span>
                    @endif
                </div>
                <div class="swahili-box">
                    <p id="text-sw" class="swahili-text" lang="{{ $hadith->has_swahili ? 'sw' : 'en' }}">{{ $hadith->display_text }}</p>
                </div>
            </section>

            {{-- 3. Tafsiri ya Kiingereza (kama ipo) --}}
            @if($hadith->english)
                <section class="reading-section" data-reading="en" aria-labelledby="label-english">
                    <div class="section-label-row">
                        <span id="label-english" class="section-label" data-i18n="pg_h_l_en">ENGLISH TRANSLATION</span>
                    </div>
                    <div class="english-box">
                        <p id="text-en" class="english-text" lang="en">{{ $hadith->english }}</p>
                    </div>
                </section>
            @endif

            <div class="islamic-divider" role="separator" aria-hidden="true">
                <span class="divider-line"></span>
                <span class="divider-emblem">۞</span>
                <span class="divider-line"></span>
            </div>

            {{-- 4. Mafundisho na Faida za Hadithi (Lessons & Insights with Gemini AI) --}}
            <section class="reading-section lessons-section" id="hadith-lessons-section" aria-labelledby="label-lessons">
                <div class="section-label-row">
                    <span id="label-lessons" class="section-label" data-i18n="pg_h_l_les">MAFUNDISHO NA FAIDA ZA HADITHI</span>
                    <span class="section-script-badge">
                        <span>Fawaid al-Hadith</span>
                    </span>
                </div>

                {{-- Mafundisho ya awali ya database (kama yapo) --}}
                @if(!empty($hadith->hints) && count($hadith->hints) > 0)
                    <div class="lessons-deck" id="static-lessons-deck">
                        @foreach($hadith->hints as $index => $hint)
                            @if(trim((string)$hint) !== '')
                                <article class="lesson-card-item">
                                    <span class="lesson-num-badge" aria-hidden="true">{{ $loop->iteration }}</span>
                                    <div class="lesson-content">
                                        <p class="lesson-text">{{ trim((string)$hint) }}</p>
                                    </div>
                                </article>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- Mafundisho ya AI (Kiswahili & English automatically) --}}
                <div class="hadith-ai-lessons-card" id="hadith-ai-lessons-card" data-endpoint="{{ route('ai.lessons', $hadith) }}">
                    <div id="ai-lessons-content">
                        <div class="ai-lessons-trigger-box">
                            <div class="ai-lessons-trigger-info">
                                <span class="ai-lessons-sparkle">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path>
                                    </svg>
                                </span>
                                <div class="ai-lessons-trigger-text">
                                    <strong id="ai-lessons-box-title">Mafundisho na Faida za Hadithi</strong>
                                    <p id="ai-lessons-box-desc">Pata uchambuzi wa mafunzo na faida za hadithi hii kwa lugha unayosoma (Kiswahili / Kiingereza).</p>
                                </div>
                            </div>
                            <button type="button" class="ai-lessons-btn" id="btn-fetch-ai-lessons">
                                <span id="btn-fetch-ai-text">Chambua Mafundisho</span>
                                <span class="btn-arrow" aria-hidden="true">→</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- 4b. Ufafanuzi wa Hadithi Hii (Hadith Clarification Assistant) --}}
                <div class="hadith-ai-chat-card" id="hadith-ai-chat-card" data-endpoint="{{ route('ai.chat', $hadith) }}">
                    <div class="ai-chat-trigger-box" id="ai-chat-trigger-box">
                        <div class="ai-chat-trigger-info">
                            <span class="ai-chat-sparkle">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </span>
                            <div class="ai-chat-trigger-text">
                                <strong>Hujaelewa au unahitaji maelezo zaidi?</strong>
                                <p>Uliza swali au pata ufafanuzi wa kina kuhusu hadithi hii moja kwa moja.</p>
                            </div>
                        </div>
                        <button type="button" class="ai-chat-open-btn" id="btn-open-ai-chat">
                            <span class="btn-text">Uliza Ufafanuzi</span>
                            <span class="btn-arrow" aria-hidden="true">↓</span>
                        </button>
                    </div>

                    {{-- Expanded Chat Box --}}
                    <div class="ai-chat-body" id="ai-chat-body" style="display: none;">
                        <div class="ai-chat-quick-prompts">
                            <span class="ai-chat-prompts-label">Maswali ya Haraka:</span>
                            <div class="ai-chat-chips">
                                <button type="button" class="ai-chat-chip" onclick="askHadithAi('Nieleze kwa lugha nyepesi sana maana na ujumbe wa hadithi hii')">
                                    Nieleze kwa lugha nyepesi
                                </button>
                                <button type="button" class="ai-chat-chip" onclick="askHadithAi('Ni nini mukhtadha au sababu ya hadithi hii kusemwa (Asbabu al-Wurud)?')">
                                    Mukhtadha wa Hadithi
                                </button>
                                <button type="button" class="ai-chat-chip" onclick="askHadithAi('Mifano ya utekelezaji wa mafunzo ya hadithi hii katika maisha ya leo')">
                                    Mafunzo ya vitendo leo
                                </button>
                                <button type="button" class="ai-chat-chip" onclick="askHadithAi('Fafanua maneno magumu ya Kiarabu na maana zake katika hadithi hii')">
                                    Maneno magumu
                                </button>
                            </div>
                        </div>

                        <div class="ai-chat-messages" id="ai-chat-messages">
                            <div class="ai-chat-msg ai initial-welcome">
                                <div class="ai-chat-avatar" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ai-chat-bubble">
                                    <p>Assalaamu 'alaykum! Mimi ni msaidizi wako wa kielimu wa hadithi hii ({{ $hadith->chapter?->book?->collection?->name }} Na. {{ $hadith->number }}). Hujaelewa neno, mukhtadha, au unahitaji ufafanuzi zaidi wa jambo gani?</p>
                                </div>
                            </div>
                        </div>

                        <form class="ai-chat-input-row" id="hadith-ai-chat-form" onsubmit="handleHadithAiChatSubmit(event)">
                            <input type="text" id="hadith-ai-chat-input" class="ai-chat-input" placeholder="Andika swali lako kuhusu hadithi hii..." autocomplete="off" required minlength="2" maxlength="400">
                            <button type="submit" id="hadith-ai-chat-send" class="ai-chat-send-btn" aria-label="Tuma swali">
                                <span>Tuma</span>
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            <div class="islamic-divider" role="separator" aria-hidden="true">
                <span class="divider-line"></span>
                <span class="divider-emblem">۞</span>
                <span class="divider-line"></span>
            </div>

            {{-- 6. Rejea na Daraja la Hadithi --}}
            <div class="source-box dua-ref-box hadith-source-box">
                <div class="source-header">
                    <h2 class="source-title" data-i18n="pg_h_src">Rejea na Daraja ya Hadithi</h2>
                </div>
                <div class="source-item">
                    <dt data-i18n="pg_h_bk">Kitabu na Mlango:</dt>
                    <dd>{{ $hadith->chapter->book->collection->name }} · <span data-i18n="pg_h_no" data-i18n-vars="{{ json_encode(['n' => $hadith->number]) }}">Hadith Na. {{ $hadith->number }}</span> ({!! $hadith->chapter->book->titleHtml() !!} · {!! $hadith->chapter->titleHtml() !!})</dd>
                </div>
                <div class="source-item">
                    <dt data-i18n="pg_h_gr">Daraja na Mpokezi:</dt>
                    @php
                        $gradeText = $hadith->grade ?? 'Sahihi';
                        $gradeKey = match (mb_strtolower(trim($gradeText))) {
                            'sahihi' => 'pg_h_gr_sahih',
                            'dhaifu' => 'pg_h_gr_daif',
                            'hasan', 'ni nzuri', 'ni nzuri (hasan)' => 'pg_h_gr_hasan',
                            default => null,
                        };
                        $attrText = $hadith->attribution ?? ('Imepokelewa na ' . $hadith->chapter->book->collection->name);
                        $attrSource = preg_match('/^Imepokele?wa na (.+)$/u', $attrText, $attrMatch) ? trim($attrMatch[1]) : null;
                    @endphp
                    <dd><span class="brief-grade-badge" @if($gradeKey) data-i18n="{{ $gradeKey }}" @endif>{{ $gradeText }}</span> · @if($attrSource)<span data-i18n="pg_h_att" data-i18n-vars="{{ json_encode(['c' => $attrSource]) }}">{{ $attrText }}</span>@else{{ $attrText }}@endif</dd>
                </div>
                @if($hadith->reference_url)
                    <div class="source-item">
                        <dt data-i18n="pg_h_ref">Rejea Rasmi:</dt>
                        <dd><a href="{{ $hadith->reference_url }}" target="_blank" rel="noopener noreferrer" data-i18n="pg_h_sunnah">Linganisha kwenye Sunnah.com ↗</a></dd>
                    </div>
                @endif
                @if($hadith->translation_source_url || $hadith->translator)
                    <div class="source-item">
                        <dt data-i18n="pg_h_tr">Mfasiri na Chanzo:</dt>
                        <dd>
                            @if($hadith->translator){{ $hadith->translator }}@else<span data-i18n="pg_h_off_tr">Mfasiri Rasmi</span>@endif
                            @if($hadith->translation_source_url)
                                · <a href="{{ $hadith->translation_source_url }}" target="_blank" rel="noopener noreferrer" data-i18n="pg_h_trsrc">Chanzo cha Tafsiri ↗</a>
                            @endif
                        </dd>
                    </div>
                @endif
                @if($hadith->license_url)
                    <div class="source-item">
                        <dt data-i18n="pg_h_terms">Masharti ya Matumizi:</dt>
                        <dd><a href="{{ $hadith->license_url }}" target="_blank" rel="noopener noreferrer" data-i18n="pg_h_lic">Leseni ya Maudhui ↗</a></dd>
                    </div>
                @endif
                <span class="sr-only">{{ $hadith->reference }}</span>
            </div>
        </article>

        <x-sponsor-strip />

        {{-- Hadithi Nyingine Katika Mlango Huu --}}
        @if($related->isNotEmpty())
            <section class="related-section dua-related-section">
                <div class="related-header">
                    <h2 data-i18n="pg_h_more_ch">Hadithi Nyingine Katika Mlango Huu</h2>
                </div>
                <div class="related-grid">
                    @foreach($related as $rel)
                        <a href="{{ route('hadith.show', $rel) }}" class="related-card">
                            <div class="related-card-meta">
                                <span class="related-col">{{ $hadith->chapter->book->collection->name }}</span>
                                <span class="related-no">Na. {{ $rel->number }}</span>
                            </div>
                            <h3 class="related-title">Hadith {{ $rel->number }}</h3>
                            <p class="related-excerpt">{{ \Illuminate\Support\Str::limit($rel->display_text, 100) }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Hadith zinazofanana + ramani ya maarifa --}}
        @if(isset($similar))
            <section class="related-section dua-related-section">
                <div class="related-header">
                    <h2 data-i18n="pg_rs_hp_title">Hadith Zinazofanana</h2>
                    <a class="lc-link" href="{{ route('research.graph', $hadith) }}" data-i18n="pg_rs_hp_map">Ramani ya maarifa</a>
                </div>
                @if($similar->isNotEmpty())
                    <div class="related-grid">
                        @foreach($similar as $s)
                            <a href="{{ route('hadith.show', $s['hadith']) }}" class="related-card">
                                <div class="related-card-meta">
                                    <span class="related-col">{{ $s['hadith']->chapter->book->collection->name }}</span>
                                    <span class="related-no">Na. {{ $s['hadith']->number }}</span>
                                </div>
                                <p class="related-excerpt">{{ \Illuminate\Support\Str::limit($s['hadith']->display_text, 100) }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif

        {{-- Urambazaji wa Chini (Next/Prev Navigation Cards) --}}
        <nav class="reader-bottom-nav dua-bottom-nav" aria-label="Urambazaji wa Hadithi">
            @if($prev)
                <a href="{{ route('hadith.show', $prev) }}" class="reader-nav-card nav-prev">
                    <span class="nav-card-icon" aria-hidden="true">←</span>
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label" data-i18n="pg_h_prev">HADITH ILIYOTANGULIA</span>
                        <strong class="nav-card-title nav-surah-name">Na. {{ $prev->number }}</strong>
                        <span class="nav-card-excerpt">{{ \Illuminate\Support\Str::limit($prev->display_text, 60) }}</span>
                    </div>
                </a>
            @else
                <div class="reader-nav-card nav-disabled">
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label" data-i18n="pg_h_start">MWANZO WA MLANGO</span>
                        <strong class="nav-card-title nav-surah-name" data-i18n="pg_h_first">Hadith ya Kwanza</strong>
                    </div>
                </div>
            @endif

            <a href="{{ route('library', ['collection' => $hadith->chapter->book->collection->slug, 'book' => $hadith->chapter->book_id, 'chapter' => $hadith->chapter_id]) }}" class="reader-nav-card nav-center" title="Nyuma" data-i18n-title="pg_lc_back">
                <span class="nav-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </span>
                <div class="nav-card-content">
                    <span class="nav-card-hint nav-dir-label" data-i18n="pg_h_chapter">MLANGO</span>
                    <strong class="nav-card-title nav-surah-name">{!! $hadith->chapter->titleHtml() !!}</strong>
                </div>
            </a>

            @if($next)
                <a href="{{ route('hadith.show', $next) }}" class="reader-nav-card nav-next">
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label" data-i18n="pg_h_next">HADITH INAYOFUATA</span>
                        <strong class="nav-card-title nav-surah-name">Na. {{ $next->number }}</strong>
                        <span class="nav-card-excerpt">{{ \Illuminate\Support\Str::limit($next->display_text, 60) }}</span>
                    </div>
                    <span class="nav-card-icon" aria-hidden="true">→</span>
                </a>
            @else
                <div class="reader-nav-card nav-disabled">
                    <div class="nav-card-content">
                        <span class="nav-card-hint nav-dir-label" data-i18n="pg_h_end">MWISHO WA MLANGO</span>
                        <strong class="nav-card-title nav-surah-name" data-i18n="pg_h_last">Hadith ya Mwisho</strong>
                    </div>
                </div>
            @endif
        </nav>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Hadith Reader Arabic Audio Console (Sauti ya Kiarabu Pekee)
            const readerBar = document.getElementById('reader-audio-bar');
            const readerPlayBtn = document.getElementById('reader-audio-btn');
            const readerAudioEl = document.getElementById('hadith-reader-audio-element');

            if (readerBar && readerPlayBtn && readerAudioEl) {
                let isPlaying = false;
                const audioUrl = readerBar.dataset.arAudio;

                function setReaderPlayState(playing) {
                    isPlaying = playing;
                    const p = readerPlayBtn.querySelector('.icon-play');
                    const pa = readerPlayBtn.querySelector('.icon-pause');
                    if (p) p.style.display = playing ? 'none' : 'block';
                    if (pa) pa.style.display = playing ? 'block' : 'none';
                    readerBar.classList.toggle('is-playing', playing);
                }

                function playReaderAudio() {
                    if (!readerAudioEl.src || !readerAudioEl.src.includes(audioUrl)) {
                        readerAudioEl.src = audioUrl;
                    }
                    const promise = readerAudioEl.play();
                    if (promise !== undefined) {
                        promise.then(() => setReaderPlayState(true)).catch((err) => {
                            setReaderPlayState(false);
                            console.warn('Playback error:', err);
                        });
                    }
                }

                function pauseReaderAudio() {
                    readerAudioEl.pause();
                    setReaderPlayState(false);
                }

                readerAudioEl.addEventListener('playing', () => setReaderPlayState(true));
                readerAudioEl.addEventListener('ended', () => setReaderPlayState(false));
                readerAudioEl.addEventListener('pause', () => setReaderPlayState(false));
                readerAudioEl.addEventListener('error', () => setReaderPlayState(false));

                readerPlayBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (isPlaying) {
                        pauseReaderAudio();
                    } else {
                        playReaderAudio();
                    }
                });
            }

            // =========================================================================
            // Hadith Scholarly Clarification & Lessons (Multilingual & Real-time)
            // =========================================================================
            const HADITH_CLARIFY_I18N = {
                sw: {
                    btnText: 'Uliza Ufafanuzi',
                    btnTitle: 'Uliza ufafanuzi au maelezo zaidi kuhusu hadithi hii',
                    lessonsBtn: 'Chambua Mafundisho',
                    lessonsTitle: 'Mafundisho na Faida za Hadithi',
                    lessonsDesc: 'Pata uchambuzi wa mafunzo na faida za hadithi hii kwa lugha unayosoma.',
                    lessonsLoading: 'Inachambua hadithi hii…',
                    lessonsLoadingSub: 'Inaandaa mafundisho na faida za kimaadili.',
                    lessonsBadge: 'Uchambuzi wa Hadithi',
                    triggerTitle: 'Hujaelewa au unahitaji maelezo zaidi?',
                    triggerDesc: 'Uliza swali au pata ufafanuzi wa kina kuhusu hadithi hii moja kwa moja.',
                    promptsLabel: 'Maswali ya Haraka:',
                    prompts: [
                        { label: 'Nieleze kwa lugha nyepesi', query: 'Nieleze kwa lugha nyepesi sana maana na ujumbe wa hadithi hii' },
                        { label: 'Mukhtadha wa Hadithi', query: 'Ni nini mukhtadha au sababu ya hadithi hii kusemwa (Asbabu al-Wurud)?' },
                        { label: 'Mafunzo ya vitendo leo', query: 'Mifano ya utekelezaji wa mafunzo ya hadithi hii katika maisha ya leo' },
                        { label: 'Maneno magumu', query: 'Fafanua maneno magumu ya Kiarabu na maana zake katika hadithi hii' },
                    ],
                    welcome: "Assalaamu 'alaykum! Mimi ni msaidizi wako wa kielimu wa hadithi hii (:col Na. :num). Hujaelewa neno, mukhtadha, au unahitaji ufafanuzi zaidi wa jambo gani?",
                    placeholder: 'Andika swali lako kuhusu hadithi hii...',
                    send: 'Tuma',
                    errorConfig: 'Weka GEMINI_API_KEY kwenye faili la .env la seva ya baytulhadith.co.tz.',
                    errorGen: 'Haikuwezekana kupata ufafanuzi kwa sasa. Tafadhali jaribu tena.',
                    errorNet: 'Hitilafu ya mtandao. Tafadhali jaribu tena.',
                },
                en: {
                    btnText: 'Ask Clarification',
                    btnTitle: 'Ask for clarification or deeper explanation of this Hadith',
                    lessonsBtn: 'Extract Lessons & Insights',
                    lessonsTitle: 'Hadith Lessons & Insights',
                    lessonsDesc: 'Extract practical moral lessons and benefits from this Hadith in English.',
                    lessonsLoading: 'Analyzing this Hadith…',
                    lessonsLoadingSub: 'Extracting practical moral lessons and benefits.',
                    lessonsBadge: 'Hadith Insights',
                    triggerTitle: 'Need clarification or deeper explanation?',
                    triggerDesc: 'Ask a question or receive in-depth scholarly clarification on this Hadith.',
                    promptsLabel: 'Quick Questions:',
                    prompts: [
                        { label: 'Explain in simple words', query: 'Explain the core meaning and message of this Hadith in simple terms' },
                        { label: 'Historical context', query: 'What is the historical context or reason behind this Hadith (Asbab al-Wurud)?' },
                        { label: 'Practical life lessons', query: 'What are practical everyday applications of this Hadith for us today?' },
                        { label: 'Difficult Arabic terms', query: 'Explain the key Arabic terms and subtle vocabulary in this Hadith' },
                    ],
                    welcome: 'Assalamu alaykum! I am your scholarly assistant for this Hadith (:col No. :num). Would you like clarification on any term, moral lesson, or context?',
                    placeholder: 'Ask your question about this Hadith...',
                    send: 'Send',
                    errorConfig: 'Please configure GEMINI_API_KEY on the server .env file.',
                    errorGen: 'Could not generate explanation at this time. Please try again.',
                    errorNet: 'Network error. Please try again.',
                },
                ar: {
                    btnText: 'طلب توضيح',
                    btnTitle: 'طلب توضيح أو شرح مفصل حول هذا الحديث',
                    lessonsBtn: 'استخراج الفوائد',
                    lessonsTitle: 'فوائد الحديث الشريف',
                    lessonsDesc: 'استخراج الفوائد التربوية والإيمانية من هذا الحديث الشريف.',
                    lessonsLoading: 'جاري استنباط الفوائد…',
                    lessonsLoadingSub: 'إعداد الفوائد الإيمانية والعملية.',
                    lessonsBadge: 'فوائد الحديث',
                    triggerTitle: 'هل تحتاج إلى مزيد من التوضيح والشرح؟',
                    triggerDesc: 'اطرح سؤالك أو احصل على توضيح مفصل حول هذا الحديث مباشرة.',
                    promptsLabel: 'أسئلة سريعة:',
                    prompts: [
                        { label: 'شرح مبسط', query: 'اشرح لي المعنى الإجمالي لهذا الحديث بأسلوب سهل ومبسط' },
                        { label: 'سبب الورود', query: 'ما هو سبب ورود هذا الحديث والمناسبة التي قيل فيها؟' },
                        { label: 'التطبيق العملي', query: 'كيف نطبق هدايات هذا الحديث في واقعنا المعاصر؟' },
                        { label: 'معاني المفردات', query: 'وضح معاني المفردات والكلمات في هذا الحديث' },
                    ],
                    welcome: 'السلام عليكم ورحمة الله! أنا مساعدك العلمي لهذا الحديث (:col رقم :num). هل تود توضيح مفردة معينة، أو سبب الورود، أو أي استفسار آخر؟',
                    placeholder: 'اكتب سؤالك حول هذا الحديث...',
                    send: 'إرسال',
                    errorConfig: 'يرجى ضبط مفتاح GEMINI_API_KEY في ملف .env بالخادم.',
                    errorGen: 'تعذر الحصول على الإجابة حالياً. يرجى المحاولة مرة أخرى.',
                    errorNet: 'خطأ في الاتصال بالشبكة. يرجى المحاولة لاحقاً.',
                },
                es: {
                    btnText: 'Pedir Aclaración',
                    btnTitle: 'Solicitar aclaración o explicación detallada sobre este hadiz',
                    lessonsBtn: 'Extraer Enseñanzas',
                    lessonsTitle: 'Enseñanzas y Beneficios del Hadiz',
                    lessonsDesc: 'Extrae lecciones prácticas y beneficios morales de este hadiz en español.',
                    lessonsLoading: 'Analizando este hadiz…',
                    lessonsLoadingSub: 'Extrayendo lecciones prácticas y beneficios morales.',
                    lessonsBadge: 'Análisis del Hadiz',
                    triggerTitle: '¿Necesitas más explicaciones o aclaraciones?',
                    triggerDesc: 'Haz una pregunta o recibe una explicación detallada sobre este hadiz.',
                    promptsLabel: 'Preguntas Rápidas:',
                    prompts: [
                        { label: 'Explicar en palabras sencillas', query: 'Explícame el significado y mensaje principal de este hadiz de forma sencilla' },
                        { label: 'Contexto del Hadiz', query: '¿Cuál es el contexto o motivo por el que se dijo este hadiz (Asbab al-Wurud)?' },
                        { label: 'Aplicación práctica', query: '¿Cómo podemos aplicar las enseñanzas de este hadiz en nuestra vida hoy?' },
                        { label: 'Términos difíciles', query: 'Explica los términos clave y palabras difíciles en este hadiz' },
                    ],
                    welcome: '¡As-salamu alaykum! Soy tu asistente de estudio para este hadiz (:col N.º :num). ¿Qué término, contexto o duda deseas aclarar?',
                    placeholder: 'Escribe tu pregunta sobre este hadiz...',
                    send: 'Enviar',
                    errorConfig: 'Configure la clave GEMINI_API_KEY en el servidor.',
                    errorGen: 'No fue posible generar la respuesta en este momento. Inténtelo de nuevo.',
                    errorNet: 'Error de red. Inténtelo de nuevo.',
                }
            };

            function getActiveClarifyLanguage() {
                const root = document.documentElement;
                const ui = root.dataset.uiLanguage || 'sw';
                const reading = root.dataset.readingLanguage || 'both';

                if (ui === 'ar' || ui === 'es') return ui;
                if (reading === 'en' || ui === 'en') return 'en';
                return 'sw';
            }

            function syncClarifyLanguageUi() {
                const lang = getActiveClarifyLanguage();
                const t = HADITH_CLARIFY_I18N[lang] || HADITH_CLARIFY_I18N.sw;
                const col = '{{ addslashes($hadith->chapter?->book?->collection?->name ?? 'Hadithi') }}';
                const num = '{{ $hadith->number }}';

                // 1. Floating button beside back-to-top
                const floatBtn = document.getElementById('floating-hadith-ai-chat');
                if (floatBtn) {
                    floatBtn.setAttribute('title', t.btnTitle);
                    floatBtn.setAttribute('aria-label', t.btnTitle);
                    const floatTxt = floatBtn.querySelector('.btn-text');
                    if (floatTxt) floatTxt.textContent = t.btnText;
                }

                // 2. Top reader action button
                const topBtn = document.getElementById('top-btn-hadith-chat');
                if (topBtn) {
                    topBtn.setAttribute('title', t.btnTitle);
                    topBtn.setAttribute('aria-label', t.btnTitle);
                    const topTxt = topBtn.querySelector('.btn-text');
                    if (topTxt) topTxt.textContent = t.btnText;
                }

                // 3. Card open button
                const cardOpenBtn = document.getElementById('btn-open-ai-chat');
                if (cardOpenBtn) {
                    const cardTxt = cardOpenBtn.querySelector('.btn-text') || cardOpenBtn.querySelector('span:first-child');
                    if (cardTxt) cardTxt.textContent = t.btnText;
                }

                // 4. Card trigger texts
                const triggerBox = document.getElementById('ai-chat-trigger-box');
                if (triggerBox) {
                    const strong = triggerBox.querySelector('strong');
                    const p = triggerBox.querySelector('p');
                    if (strong) strong.textContent = t.triggerTitle;
                    if (p) p.textContent = t.triggerDesc;
                }

                // 5. Lessons card texts
                const lessonsTitle = document.getElementById('ai-lessons-box-title');
                const lessonsDesc = document.getElementById('ai-lessons-box-desc');
                const lessonsBtnText = document.getElementById('btn-fetch-ai-text');
                if (lessonsTitle) lessonsTitle.textContent = t.lessonsTitle;
                if (lessonsDesc) lessonsDesc.textContent = t.lessonsDesc;
                if (lessonsBtnText) lessonsBtnText.textContent = t.lessonsBtn;

                // 6. Quick Prompts
                const promptsLabel = document.querySelector('.ai-chat-prompts-label');
                if (promptsLabel) promptsLabel.textContent = t.promptsLabel;
                const chipsWrap = document.querySelector('.ai-chat-chips');
                if (chipsWrap && t.prompts) {
                    chipsWrap.innerHTML = t.prompts.map(p => `
                        <button type="button" class="ai-chat-chip" onclick="askHadithAi('${p.query.replace(/'/g, "\\'")}')">
                            ${p.label}
                        </button>
                    `).join('');
                }

                // 7. Initial Welcome message
                const initialWelcomeBubble = document.querySelector('#ai-chat-messages .ai-chat-msg.initial-welcome .ai-chat-bubble p');
                if (initialWelcomeBubble) {
                    initialWelcomeBubble.textContent = t.welcome.replace(':col', col).replace(':num', num);
                }

                // 8. Chat input & send button
                const input = document.getElementById('hadith-ai-chat-input');
                if (input) input.setAttribute('placeholder', t.placeholder);
                const sendBtn = document.getElementById('hadith-ai-chat-send');
                if (sendBtn) {
                    const sendTxt = sendBtn.querySelector('span');
                    if (sendTxt) sendTxt.textContent = t.send;
                }
            }

            // Sync on load, on language events, and on documentElement changes
            syncClarifyLanguageUi();
            document.addEventListener('bayt:lang', syncClarifyLanguageUi);
            new MutationObserver(syncClarifyLanguageUi).observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['data-ui-language', 'data-reading-language']
            });

            // Lessons trigger
            const aiLessonsBtn = document.getElementById('btn-fetch-ai-lessons');
            const aiLessonsContent = document.getElementById('ai-lessons-content');
            const aiLessonsCard = document.getElementById('hadith-ai-lessons-card');

            if (aiLessonsBtn && aiLessonsContent && aiLessonsCard) {
                const endpoint = aiLessonsCard.getAttribute('data-endpoint');

                aiLessonsBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const lang = getActiveClarifyLanguage();
                    const t = HADITH_CLARIFY_I18N[lang] || HADITH_CLARIFY_I18N.sw;
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                    aiLessonsContent.innerHTML = `
                        <div class="ai-lessons-loading">
                            <span class="ai-lessons-spinner" aria-hidden="true"></span>
                            <div class="ai-lessons-loading-text">
                                <strong>${t.lessonsLoading}</strong>
                                <span>${t.lessonsLoadingSub}</span>
                            </div>
                        </div>
                    `;

                    fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({ lang: lang })
                    })
                    .then(r => r.json().then(data => ({ status: r.status, data })))
                    .then(({ status, data }) => {
                        if (data.error === 'not_configured') {
                            aiLessonsContent.innerHTML = `<div class="ai-lessons-msg error">⚙️ ${data.message || t.errorConfig}</div>`;
                            return;
                        }
                        if (status >= 400 || !data.lessons || data.lessons.length === 0) {
                            aiLessonsContent.innerHTML = `
                                <div class="ai-lessons-msg error">
                                    <span>⚠️ ${t.errorGen}</span>
                                </div>
                            `;
                            return;
                        }

                        const lessonsHtml = data.lessons.map((lesson, idx) => `
                            <article class="lesson-card-item ai-lesson-card">
                                <span class="lesson-num-badge ai-badge" aria-hidden="true">${idx + 1}</span>
                                <div class="lesson-content">
                                    <p class="lesson-text">${lesson.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                                </div>
                            </article>
                        `).join('');

                        aiLessonsContent.innerHTML = `
                            <div class="ai-lessons-results">
                                <div class="ai-lessons-header-strip">
                                    <span class="ai-badge-pill">${t.lessonsBadge}</span>
                                    <button type="button" class="ai-refresh-btn" id="btn-re-fetch-lessons" title="Sasisha">↻</button>
                                </div>
                                <div class="lessons-deck">
                                    ${lessonsHtml}
                                </div>
                            </div>
                        `;

                        const refetchBtn = document.getElementById('btn-re-fetch-lessons');
                        if (refetchBtn) {
                            refetchBtn.addEventListener('click', function() {
                                aiLessonsBtn.click();
                            });
                        }
                    })
                    .catch(err => {
                        console.error('Lessons error:', err);
                        aiLessonsContent.innerHTML = `<div class="ai-lessons-msg error">⚠️ ${t.errorNet}</div>`;
                    });
                });
            }

            // Hadith Clarification Interactive Discussion
            const chatCard = document.getElementById('hadith-ai-chat-card');
            const openChatBtn = document.getElementById('btn-open-ai-chat');
            const topChatBtn = document.getElementById('top-btn-hadith-chat');
            const chatBody = document.getElementById('ai-chat-body');
            const chatMessages = document.getElementById('ai-chat-messages');
            const chatInput = document.getElementById('hadith-ai-chat-input');
            const chatForm = document.getElementById('hadith-ai-chat-form');

            function toggleHadithAiChat() {
                if (!chatBody) return;
                const isHidden = chatBody.style.display === 'none';
                chatBody.style.display = isHidden ? 'block' : 'none';
                if (openChatBtn) {
                    const arrow = openChatBtn.querySelector('.btn-arrow');
                    if (arrow) arrow.textContent = isHidden ? '↑' : '↓';
                }
                if (isHidden && chatCard) {
                    chatCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    if (chatInput) setTimeout(() => chatInput.focus(), 300);
                }
            }

            function openAndFocusHadithAiChat() {
                if (!chatBody) return;
                if (chatBody.style.display === 'none') {
                    chatBody.style.display = 'block';
                    if (openChatBtn) {
                        const arrow = openChatBtn.querySelector('.btn-arrow');
                        if (arrow) arrow.textContent = '↑';
                    }
                }
                if (chatCard) {
                    chatCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    if (chatInput) setTimeout(() => chatInput.focus(), 350);
                }
            }

            if (openChatBtn) openChatBtn.addEventListener('click', toggleHadithAiChat);
            if (topChatBtn) topChatBtn.addEventListener('click', openAndFocusHadithAiChat);
            const floatingChatBtn = document.getElementById('floating-hadith-ai-chat');
            if (floatingChatBtn) floatingChatBtn.addEventListener('click', openAndFocusHadithAiChat);

            const aiChatSvgAvatar = `
                <div class="ai-chat-avatar" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
            `;

            window.askHadithAi = function(query) {
                if (!query) return;
                if (chatBody && chatBody.style.display === 'none') {
                    chatBody.style.display = 'block';
                    if (openChatBtn) {
                        const arrow = openChatBtn.querySelector('.btn-arrow');
                        if (arrow) arrow.textContent = '↑';
                    }
                }
                sendHadithChatMessage(query);
            };

            window.handleHadithAiChatSubmit = function(e) {
                e.preventDefault();
                if (!chatInput) return;
                const q = chatInput.value.trim();
                if (q) {
                    sendHadithChatMessage(q);
                    chatInput.value = '';
                }
            };

            function sendHadithChatMessage(msg) {
                if (!chatMessages || !chatCard) return;
                const endpoint = chatCard.getAttribute('data-endpoint');
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const lang = getActiveClarifyLanguage();
                const t = HADITH_CLARIFY_I18N[lang] || HADITH_CLARIFY_I18N.sw;

                // Append user message
                const userBubble = document.createElement('div');
                userBubble.className = 'ai-chat-msg user';
                userBubble.innerHTML = `
                    <div class="ai-chat-bubble">
                        <p>${msg.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                    </div>
                `;
                chatMessages.appendChild(userBubble);

                // Append loading bubble
                const loadingBubble = document.createElement('div');
                loadingBubble.className = 'ai-chat-msg ai loading';
                loadingBubble.innerHTML = `
                    ${aiChatSvgAvatar}
                    <div class="ai-chat-bubble">
                        <div class="ai-chat-typing">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                `;
                chatMessages.appendChild(loadingBubble);
                chatMessages.scrollTop = chatMessages.scrollHeight;

                fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ message: msg, lang: lang })
                })
                .then(r => r.json().then(data => ({ status: r.status, data })))
                .then(({ status, data }) => {
                    loadingBubble.remove();
                    const aiBubble = document.createElement('div');
                    aiBubble.className = 'ai-chat-msg ai';

                    if (data.error === 'not_configured') {
                        aiBubble.innerHTML = `
                            <div class="ai-chat-avatar">⚙️</div>
                            <div class="ai-chat-bubble error">
                                <p>${data.message || t.errorConfig}</p>
                            </div>
                        `;
                    } else if (status >= 400 || !data.answer) {
                        aiBubble.innerHTML = `
                            <div class="ai-chat-avatar">⚠️</div>
                            <div class="ai-chat-bubble error">
                                <p>${t.errorGen}</p>
                            </div>
                        `;
                    } else {
                        const formatted = data.answer
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/\n\n+/g, '</p><p>')
                            .replace(/\n/g, '<br>');

                        aiBubble.innerHTML = `
                            ${aiChatSvgAvatar}
                            <div class="ai-chat-bubble">
                                <p>${formatted}</p>
                            </div>
                        `;
                    }
                    chatMessages.appendChild(aiBubble);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                })
                .catch(err => {
                    console.error('Chat error:', err);
                    loadingBubble.remove();
                    const errBubble = document.createElement('div');
                    errBubble.className = 'ai-chat-msg ai';
                    errBubble.innerHTML = `
                        <div class="ai-chat-avatar">⚠️</div>
                        <div class="ai-chat-bubble error">
                            <p>${t.errorNet}</p>
                        </div>
                    `;
                    chatMessages.appendChild(errBubble);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });
            }

            const playBtn = document.getElementById('audio-play');
            if (playBtn) {
                let isSpeechPlaying = false;
                playBtn.addEventListener('click', () => {
                    if (!('speechSynthesis' in window)) {
                        alert('Kivinjari chako hakitumii usomaji wa sauti wa moja kwa moja.');
                        return;
                    }

                    if (isSpeechPlaying) {
                        window.speechSynthesis.cancel();
                        isSpeechPlaying = false;
                        playBtn.querySelector('.btn-text').textContent = 'Soma kwa sauti';
                        playBtn.classList.remove('active');
                        return;
                    }

                    window.speechSynthesis.cancel();
                    const textToRead = (arabicEl?.innerText || swahiliEl?.innerText || '').trim();
                    if (!textToRead) return;

                    const utterance = new SpeechSynthesisUtterance(textToRead);
                    utterance.lang = 'ar-SA';
                    utterance.rate = 0.92;
                    utterance.pitch = 0.95; // Sauti ya kiume ya Sheikh

                    if ('speechSynthesis' in window) {
                        const voices = window.speechSynthesis.getVoices();
                        const arVoices = voices.filter(v => (v.lang || '').startsWith('ar'));
                        const maleVoice = arVoices.find(v => {
                            const n = (v.name || '').toLowerCase();
                            return n.includes('majed') || n.includes('naif') || n.includes('tarik') ||
                                   n.includes('maged') || n.includes('hamed') || n.includes('shakir') ||
                                   n.includes('male') || n.includes('bassam');
                        }) || arVoices[0];
                        if (maleVoice) utterance.voice = maleVoice;
                    }

                    utterance.onend = utterance.onerror = () => {
                        isSpeechPlaying = false;
                        playBtn.querySelector('.btn-text').textContent = 'Soma kwa sauti';
                        playBtn.classList.remove('active');
                    };

                    window.speechSynthesis.speak(utterance);
                    isSpeechPlaying = true;
                    playBtn.querySelector('.btn-text').textContent = 'Sitisha sauti';
                    playBtn.classList.add('active');
                });
            }

            // Reader More Actions Menu Dropdown
            const moreBtn = document.getElementById('reader-more-btn');
            const moreDropdown = document.getElementById('reader-more-dropdown');
            if (moreBtn && moreDropdown) {
                moreBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isHidden = moreDropdown.hasAttribute('hidden');
                    if (isHidden) {
                        moreDropdown.removeAttribute('hidden');
                        moreBtn.setAttribute('aria-expanded', 'true');
                    } else {
                        moreDropdown.setAttribute('hidden', '');
                        moreBtn.setAttribute('aria-expanded', 'false');
                    }
                });
                document.addEventListener('click', function (e) {
                    if (!moreDropdown.contains(e.target) && e.target !== moreBtn) {
                        moreDropdown.setAttribute('hidden', '');
                        moreBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // Direct Copy Button in Dropdown
            const copyDirectBtn = document.getElementById('reader-copy-direct');
            if (copyDirectBtn) {
                copyDirectBtn.addEventListener('click', async function (e) {
                    e.stopPropagation();
                    const shareBtn = document.getElementById('share-hadith');
                    const textToCopy = shareBtn ? window.baytShareText(shareBtn) : window.location.href;
                    try {
                        await navigator.clipboard.writeText(textToCopy);
                        const label = document.getElementById('reader-copy-label');
                        const icon = document.getElementById('reader-copy-icon');
                        const origLabel = label ? label.textContent : '';
                        const origIcon = icon ? icon.innerHTML : '';
                        if (label) label.textContent = (document.documentElement.dataset.readingLanguage === 'en') ? '✓ Copied!' : '✓ Imenakiliwa!';
                        if (icon) icon.innerHTML = '<span style="color:var(--gold);font-weight:700;">✓</span>';
                        setTimeout(() => {
                            if (label) label.textContent = origLabel || 'Nakili Matini';
                            if (icon) icon.innerHTML = origIcon;
                        }, 2200);
                    } catch (_) {}
                });
            }

            // Direct Native Share in Dropdown
            const nativeShareBtn = document.getElementById('share-hadith');
            if (nativeShareBtn) {
                nativeShareBtn.addEventListener('click', async function (e) {
                    if (navigator.share) {
                        e.preventDefault();
                        e.stopPropagation();
                        try {
                            await navigator.share({
                                title: this.dataset.shareTitle || document.title,
                                text: window.baytShareText(this),
                                url: this.dataset.shareUrl
                            });
                            if (moreDropdown) {
                                moreDropdown.setAttribute('hidden', '');
                                moreBtn.setAttribute('aria-expanded', 'false');
                            }
                        } catch (_) {}
                    }
                });
            }
        });
    </script>
    <script>
        // Ujumbe wa kushiriki/kunakili unafuata lugha ya hadith (Kiswahili au English)
        (function () {
            var root = document.documentElement;
            var isEn = function () { return root.dataset.readingLanguage === 'en'; };
            window.baytShareText = function (btn) {
                var en = isEn() && btn.dataset.shareTextEn;
                return (en ? btn.dataset.shareTextEn : btn.dataset.shareText) + '\n\n' + (isEn() ? 'Read more' : 'Soma zaidi') + ': ' + btn.dataset.shareUrl;
            };
            var sync = function () {
                var b = document.getElementById('share-hadith');
                var a = document.getElementById('reader-whatsapp-direct');
                if (b && a) a.href = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(window.baytShareText(b));
            };
            sync();
            new MutationObserver(sync).observe(root, { attributes: true, attributeFilter: ['data-reading-language'] });
        })();
    </script>
    <script src="{{ asset('js/share-card.js') }}?v={{ filemtime(public_path('js/share-card.js')) }}" defer></script>
</x-layouts.app>
