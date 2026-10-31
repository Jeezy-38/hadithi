<footer class="site-footer" role="contentinfo">
    <div class="footer-container">
        <div class="footer-top-grid">
            <!-- Brand & Logo -->
            <div class="footer-brand-wrap">
                <a href="{{ route('library') }}" class="footer-brand-card" aria-label="Bayt Al-Hadith - Mwanzo">
                    <div class="footer-logo-badge">
                        <img src="{{ asset('images/logo.png') }}" alt="Bayt Al-Hadith" class="footer-logo-img">
                    </div>
                    <div class="footer-brand-headings">
                        <span class="footer-brand-name">Bayt Al-<span>Hadith</span></span>
                        <span class="footer-brand-sub">Maktaba Kuu ya Hadithi za Mtume ﷺ</span>
                    </div>
                </a>
            </div>

            <!-- Important Links -->
            <div class="footer-important-links">
                <h3 class="footer-section-title">Important Links</h3>
                <ul class="footer-links-list">
                    <li>
                        <a href="{{ route('qibla') }}" class="footer-link-item">
                            <span class="footer-link-bullet">✦</span>
                            <span>Qibla direction location Dar es Salaam</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('islamic.date') }}" class="footer-link-item">
                            <span class="footer-link-bullet">✦</span>
                            <span>Islamic date</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ramadhan') }}" class="footer-link-item">
                            <span class="footer-link-bullet">✦</span>
                            <span>Ramadhan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('prayer.times') }}" class="footer-link-item">
                            <span class="footer-link-bullet">✦</span>
                            <span>Live prayer time counter</span>
                        </a>
                    </li>
                </ul>

                <h3 class="footer-section-title footer-related-title" data-i18n="pg_ft_related">Kurasa Zinazohusiana</h3>
                <ul class="footer-links-list">
                    <li>
                        <a href="{{ route('quran.index') }}" class="footer-link-item">
                            <span class="footer-link-bullet">✦</span>
                            <span data-i18n="pg_ft_quran">Qur'ani Tukufu</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('duaa.index') }}" class="footer-link-item">
                            <span class="footer-link-bullet">✦</span>
                            <span data-i18n="pg_ft_duaa">Dua & Adhkar</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('topics.index') }}" class="footer-link-item">
                            <span class="footer-link-bullet">✦</span>
                            <span data-i18n="pg_ft_topics">Mada za Kiislamu</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Shiriki na Wengine -->
            <div class="footer-social-wrap">
                <h3 class="footer-section-title">Shiriki na Wengine</h3>
                <p class="footer-social-desc">Sambaza mafundisho sahihi ya Sunnah:</p>
                <div class="footer-social-links">
                    <a href="https://api.whatsapp.com/send?text={{ urlencode('Bayt Al-Hadith — Maktaba ya Kidijitali ya Hadithi za Mtume ﷺ: ' . url('/')) }}" target="_blank" rel="noopener noreferrer" class="social-share-btn share-whatsapp" aria-label="Shiriki WhatsApp" title="WhatsApp">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 2C6.48 2 2 6.48 2 12C2 13.85 2.5 15.58 3.38 17.07L2.05 21.95L7.07 20.65C8.52 21.5 10.2 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2Z" fill="#25D366"/>
                            <path d="M17.47 14.88C17.17 14.73 15.69 14 15.42 13.9C15.14 13.8 14.94 13.75 14.74 14.05C14.54 14.35 13.97 15.02 13.8 15.22C13.62 15.42 13.45 15.44 13.15 15.29C12.85 15.14 11.89 14.83 10.75 13.81C9.86 13.02 9.26 12.04 9.09 11.74C8.91 11.44 9.07 11.28 9.22 11.13C9.35 11 9.52 10.78 9.67 10.61C9.82 10.44 9.87 10.31 9.97 10.11C10.07 9.91 10.02 9.74 9.95 9.59C9.87 9.44 9.27 7.97 9.02 7.37C8.78 6.78 8.53 6.86 8.35 6.85C8.18 6.84 7.98 6.84 7.78 6.84C7.58 6.84 7.26 6.92 6.98 7.22C6.71 7.52 5.93 8.25 5.93 9.73C5.93 11.21 7.01 12.64 7.16 12.84C7.31 13.04 9.27 16.06 12.28 17.36C12.99 17.67 13.56 17.86 13.99 18C14.71 18.23 15.37 18.19 15.89 18.12C16.47 18.03 17.67 17.39 17.92 16.69C18.17 15.99 18.17 15.39 18.09 15.26C18.02 15.14 17.77 15.03 17.47 14.88Z" fill="white"/>
                        </svg>
                    </a>

                    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" class="social-share-btn share-instagram" aria-label="Instagram" title="Instagram">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <defs>
                                <radialGradient id="igFooterGrad" cx="30%" cy="105%" r="120%">
                                    <stop offset="0%" stop-color="#ffdb76"/>
                                    <stop offset="10%" stop-color="#fca547"/>
                                    <stop offset="30%" stop-color="#e73752"/>
                                    <stop offset="60%" stop-color="#b025a1"/>
                                    <stop offset="100%" stop-color="#4c64d3"/>
                                </radialGradient>
                            </defs>
                            <rect x="2" y="2" width="20" height="20" rx="6" fill="url(#igFooterGrad)"/>
                            <rect x="6" y="6" width="12" height="12" rx="3.5" stroke="white" stroke-width="1.8" fill="none"/>
                            <circle cx="12" cy="12" r="3" stroke="white" stroke-width="1.8" fill="none"/>
                            <circle cx="15.5" cy="8.5" r="0.9" fill="white"/>
                        </svg>
                    </a>

                    <a href="https://twitter.com/intent/tweet?text={{ urlencode('Bayt Al-Hadith — Maktaba ya Kidijitali ya Hadithi za Mtume ﷺ') }}&url={{ urlencode(url('/')) }}" target="_blank" rel="noopener noreferrer" class="social-share-btn share-x" aria-label="Shiriki X (Twitter)" title="X (Twitter)">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <rect x="2" y="2" width="20" height="20" rx="6" fill="#000000" stroke="rgba(255,255,255,0.2)" stroke-width="1"/>
                            <path d="M16.3 6H18.2L14 10.8L18.9 17.3H15.1L12.1 13.4L8.7 17.3H6.8L11.3 12.2L6.6 6H10.5L13.2 9.6L16.3 6ZM15.6 16.2H16.7L9.9 7.1H8.8L15.6 16.2Z" fill="white"/>
                        </svg>
                    </a>

                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/')) }}" target="_blank" rel="noopener noreferrer" class="social-share-btn share-facebook" aria-label="Shiriki Facebook" title="Facebook">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <rect x="2" y="2" width="20" height="20" rx="6" fill="#1877F2"/>
                            <path d="M14.5 12.5H12.5V19H9.8V12.5H8.5V10.2H9.8V8.7C9.8 7.3 10.5 6 12.7 6C13.6 6 14.3 6.1 14.3 6.1L14.2 8.2C13.8 8.2 13.3 8.2 12.8 8.2C12.3 8.2 12.2 8.4 12.2 8.9V10.2H14.4L14.5 12.5Z" fill="white"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom-bar">
            <p class="footer-copy-text">
                &copy; {{ date('Y') }} Bayt Al-Hadith. Haki zote zimehifadhiwa.
            </p>
        </div>
    </div>
</footer>
