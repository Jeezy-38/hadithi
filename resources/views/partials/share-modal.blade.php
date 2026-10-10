{{-- Modal ya Kisasa ya Kushiriki Hadithi, Aya na Dua (Modern Share Sheet & Card Generator) --}}
<div id="share-modal" class="share-modal-backdrop" role="dialog" aria-labelledby="share-modal-title" aria-modal="true" hidden>
    <div class="share-modal-overlay" id="share-modal-overlay"></div>
    <div class="share-modal-dialog">
        <div class="share-modal-header">
            <div class="share-modal-title-wrap">
                <span class="share-modal-eyebrow">SHIRIKI ELIMU & MAFUNDISHO</span>
                <h3 id="share-modal-title" class="share-modal-title">Shiriki Ujumbe</h3>
            </div>
            <button type="button" class="share-modal-close" id="share-modal-close" aria-label="Funga dirisha la kushiriki">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="share-modal-body">
            {{-- Kisanduku cha Onyesho la Awali (Preview Box) --}}
            <div class="share-preview-card" id="share-preview-card">
                <div class="share-preview-badge" id="share-preview-badge">Hadithi</div>
                <div class="share-preview-ar font-arabic" id="share-preview-ar" dir="rtl"></div>
                <div class="share-preview-sw" id="share-preview-sw"></div>
                <div class="share-preview-ref" id="share-preview-ref"></div>
            </div>

            {{-- Vitufe vya Vitendo (Action Buttons) --}}
            <div class="share-actions-grid">
                {{-- 1. WhatsApp --}}
                <a href="#" id="share-action-whatsapp" class="share-action-btn btn-whatsapp" target="_blank" rel="noopener noreferrer">
                    <span class="share-btn-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.23 8.23 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24m4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.4-1.75-.15-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.12-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.78 2.71 4.3 3.8.6.26 1.07.42 1.44.54.61.19 1.16.17 1.6.1.49-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/>
                        </svg>
                    </span>
                    <span class="share-btn-text">WhatsApp</span>
                </a>

                {{-- 2. Pakua Picha (Image Card) --}}
                <button type="button" id="share-action-image" class="share-action-btn btn-image">
                    <span class="share-btn-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </span>
                    <span class="share-btn-text">Kadi ya Picha</span>
                </button>

                {{-- 3. Nakili Matini --}}
                <button type="button" id="share-action-copy" class="share-action-btn btn-copy">
                    <span class="share-btn-icon" id="share-copy-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                    </span>
                    <span class="share-btn-text" id="share-copy-label">Nakili Matini</span>
                </button>

                {{-- 4. Shiriki Zaidi (Native Sheet) --}}
                <button type="button" id="share-action-native" class="share-action-btn btn-native">
                    <span class="share-btn-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="5" r="3"></circle>
                            <circle cx="6" cy="12" r="3"></circle>
                            <circle cx="18" cy="19" r="3"></circle>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                        </svg>
                    </span>
                    <span class="share-btn-text">Mitandao Mingine</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Canvas iliyofichwa kwa ajili ya kutengeneza picha (Hidden Card Canvas) --}}
<canvas id="share-card-canvas" width="1080" height="1080" style="display: none;"></canvas>
