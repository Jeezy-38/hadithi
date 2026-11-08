{{-- Kitufe cha Kuelea cha Maoni & Modal (Floating Feedback Widget) --}}
@unless(request()->is('admin*'))
<div class="feedback-widget-root" id="feedback-widget-root">
    {{-- Floating Action Button --}}
    <button type="button" class="feedback-floating-btn" id="open-feedback-btn" aria-label="Toa Maoni au Pendekezo" title="Toa Maoni & Pendekeza Maboresho">
        <span class="feedback-btn-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
        </span>
        <span class="feedback-btn-text" data-i18n="feedback_btn">Maoni</span>
    </button>

    {{-- Feedback Modal Backdrop & Dialog --}}
    <div class="feedback-modal-backdrop" id="feedback-backdrop" hidden></div>
    <div class="feedback-modal-dialog" id="feedback-modal" role="dialog" aria-modal="true" aria-labelledby="feedback-title" aria-hidden="true" hidden>
        <div class="feedback-modal-content">
            {{-- Header --}}
            <div class="feedback-modal-header">
                <div>
                    <span class="feedback-modal-eyebrow">BAYT UL HADITH</span>
                    <h3 class="feedback-modal-title" id="feedback-title">Toa Maoni & Mawazo</h3>
                    <p class="feedback-modal-sub">Mawazo yako yanasaidia kufanya jukwaa hili liwe bora zaidi.</p>
                </div>
                <button type="button" class="feedback-close-btn" id="close-feedback-btn" aria-label="Funga dirisha">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            {{-- Form View --}}
            <form id="feedback-form" class="feedback-form" onsubmit="submitUserFeedback(event)">
                @csrf
                <input type="hidden" name="page_url" id="fb-page-url" value="{{ url()->current() }}">
                <input type="hidden" name="category" id="fb-category" value="feature">

                {{-- Category Chips --}}
                <div class="feedback-form-group">
                    <label class="feedback-label">Aina ya Maoni:</label>
                    <div class="feedback-chips" role="radiogroup" aria-label="Aina ya Maoni">
                        <button type="button" class="feedback-chip active" data-cat="feature" onclick="selectFeedbackCategory('feature', this)">
                            💡 Wazo Jipya
                        </button>
                        <button type="button" class="feedback-chip" data-cat="correction" onclick="selectFeedbackCategory('correction', this)">
                            ✍️ Sahihisho
                        </button>
                        <button type="button" class="feedback-chip" data-cat="bug" onclick="selectFeedbackCategory('bug', this)">
                            🐛 Hitilafu
                        </button>
                        <button type="button" class="feedback-chip" data-cat="general" onclick="selectFeedbackCategory('general', this)">
                            💬 Ushauri Mkuu
                        </button>
                    </div>
                </div>

                {{-- Message Textarea --}}
                <div class="feedback-form-group">
                    <label for="fb-message" class="feedback-label">Ujumbe / Wazo Lako: <span style="color: #ef4444;">*</span></label>
                    <textarea id="fb-message" name="message" class="feedback-textarea" rows="4" required minlength="5" maxlength="3000" placeholder="Eleza kwa ufupi wazo lako, kipengele unachotamani kiongezwe, au hitilafu uliyoiona..."></textarea>
                </div>

                {{-- Contact Fields (Optional) --}}
                <div class="feedback-form-row">
                    <div class="feedback-form-group" style="flex: 1;">
                        <label for="fb-name" class="feedback-label">Jina Lako (Si Lazima):</label>
                        <input type="text" id="fb-name" name="name" class="feedback-input" value="{{ auth()->check() ? auth()->user()->name : '' }}" placeholder="mf. Abubakar">
                    </div>
                    <div class="feedback-form-group" style="flex: 1;">
                        <label for="fb-contact" class="feedback-label">Email au WhatsApp (Si Lazima):</label>
                        <input type="text" id="fb-contact" name="contact" class="feedback-input" value="{{ auth()->check() ? auth()->user()->email : '' }}" placeholder="mf. barua@mfano.com">
                    </div>
                </div>

                {{-- Error Container --}}
                <div id="fb-error-msg" class="feedback-error" style="display: none;"></div>

                {{-- Submit Button --}}
                <div class="feedback-modal-footer">
                    <button type="submit" id="fb-submit-btn" class="feedback-submit-btn">
                        <span>Tuma Wazo / Maoni</span>
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </div>
            </form>

            {{-- Success State --}}
            <div id="feedback-success" class="feedback-success" style="display: none;">
                <div class="feedback-success-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h4 class="feedback-success-title">Shukran Sana!</h4>
                <p class="feedback-success-desc" id="fb-success-text">
                    Wazo na maoni yako yamepokelewa vizuri. Timu yetu itayafanyia kazi kwa ajili ya kuboresha Bayt Ul Hadith.
                </p>
                <button type="button" class="feedback-submit-btn" style="width: auto; margin: 20px auto 0;" onclick="closeFeedbackModal()">
                    Funga Dirisha
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const openBtn = document.getElementById('open-feedback-btn');
        const modal = document.getElementById('feedback-modal');
        const backdrop = document.getElementById('feedback-backdrop');
        const closeBtn = document.getElementById('close-feedback-btn');

        function openModal() {
            if (!modal || !backdrop) return;
            backdrop.hidden = false;
            modal.hidden = false;
            requestAnimationFrame(() => {
                backdrop.classList.add('is-open');
                modal.classList.add('is-open');
            });
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('feedback-modal-open');
            const textarea = document.getElementById('fb-message');
            if (textarea) setTimeout(() => textarea.focus(), 150);
        }

        function closeModal() {
            if (!modal || !backdrop) return;
            modal.classList.remove('is-open');
            backdrop.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('feedback-modal-open');
            setTimeout(() => {
                modal.hidden = true;
                backdrop.hidden = true;
            }, 250);
        }

        window.closeFeedbackModal = closeModal;

        if (openBtn) openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal && !modal.hidden) closeModal();
        });
    })();

    function selectFeedbackCategory(cat, el) {
        document.getElementById('fb-category').value = cat;
        document.querySelectorAll('.feedback-chip').forEach(btn => btn.classList.remove('active'));
        if (el) el.classList.add('active');
    }

    async function submitUserFeedback(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('fb-submit-btn');
        const errorDiv = document.getElementById('fb-error-msg');
        const form = document.getElementById('feedback-form');
        const successBox = document.getElementById('feedback-success');

        errorDiv.style.display = 'none';
        submitBtn.disabled = true;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = 'Inatuma...';

        const payload = {
            category: document.getElementById('fb-category').value,
            message: document.getElementById('fb-message').value,
            name: document.getElementById('fb-name').value,
            contact: document.getElementById('fb-contact').value,
            page_url: window.location.href,
            hadith_ref: document.querySelector('[data-hadith-ref]')?.getAttribute('data-hadith-ref') || null
        };

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('{{ route('api.feedback.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token || ''
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();
            if (res.ok && data.success) {
                form.style.display = 'none';
                successBox.style.display = 'block';
                form.reset();
            } else {
                errorDiv.textContent = data.message || 'Hitilafu imetokea wakati wa kutuma. Tafadhali jaribu tena.';
                errorDiv.style.display = 'block';
            }
        } catch (_) {
            errorDiv.textContent = 'Hitilafu ya muunganisho wa mtandao. Tafadhali jaribu tena.';
            errorDiv.style.display = 'block';
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
</script>
@endunless
