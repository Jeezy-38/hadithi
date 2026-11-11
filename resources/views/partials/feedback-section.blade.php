{{-- Fomu ya Kutoa Maoni na Ushauri (Chini ya Collection) --}}
<section class="inpage-feedback-section" id="fomu-ya-maoni" aria-label="Toa Maoni na Ushauri">
    <div class="inpage-feedback-card">
        <div class="inpage-feedback-header">
            <div class="inpage-feedback-badge">
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <span class="inpage-feedback-badge-text">BAYT UL HADITH</span>
            </div>
            <h3 class="inpage-feedback-title">Toa Maoni & Pendekeza Maboresho</h3>
            <p class="inpage-feedback-desc">Una wazo jipya, sahihisho la hadithi, au hitilafu uliyoiona? Maoni yako yanatusaidia kufanya jukwaa hili liwe bora zaidi kwa jamii.</p>
        </div>

        <form id="inpage-feedback-form" class="inpage-feedback-form" onsubmit="submitInpageFeedback(event)">
            @csrf
            <input type="hidden" name="category" id="inpage-fb-category" value="feature">

            {{-- Category Chips --}}
            <div class="feedback-form-group">
                <label class="feedback-label">Aina ya Maoni:</label>
                <div class="feedback-chips" role="radiogroup" aria-label="Aina ya Maoni">
                    <button type="button" class="feedback-chip active" data-cat="feature" onclick="selectInpageFeedbackCat('feature', this)">
                        Wazo Jipya
                    </button>
                    <button type="button" class="feedback-chip" data-cat="correction" onclick="selectInpageFeedbackCat('correction', this)">
                        Sahihisho
                    </button>
                    <button type="button" class="feedback-chip" data-cat="bug" onclick="selectInpageFeedbackCat('bug', this)">
                        Hitilafu
                    </button>
                    <button type="button" class="feedback-chip" data-cat="general" onclick="selectInpageFeedbackCat('general', this)">
                        Ushauri Mkuu
                    </button>
                </div>
            </div>

            {{-- Message Textarea --}}
            <div class="feedback-form-group">
                <label for="inpage-fb-message" class="feedback-label">Ujumbe / Wazo Lako: <span style="color: #ef4444;">*</span></label>
                <textarea id="inpage-fb-message" name="message" class="feedback-textarea" rows="4" required minlength="5" maxlength="3000" placeholder="Eleza kwa ufupi wazo lako, kipengele unachotamani kiongezwe, au ushauri wako..."></textarea>
            </div>

            {{-- Contact Fields (Optional) --}}
            <div class="feedback-form-row">
                <div class="feedback-form-group" style="flex: 1;">
                    <label for="inpage-fb-name" class="feedback-label">Jina Lako (Si Lazima):</label>
                    <input type="text" id="inpage-fb-name" name="name" class="feedback-input" value="{{ auth()->check() ? auth()->user()->name : '' }}" placeholder="mf. Abubakar">
                </div>
                <div class="feedback-form-group" style="flex: 1;">
                    <label for="inpage-fb-contact" class="feedback-label">Email au Namba ya Simu (Si Lazima):</label>
                    <input type="text" id="inpage-fb-contact" name="contact" class="feedback-input" value="{{ auth()->check() ? auth()->user()->email : '' }}" placeholder="mf. barua@mfano.com au 07...">
                </div>
            </div>

            <div id="inpage-fb-error-msg" class="feedback-error" style="display: none; padding: 10px 14px; border-radius: 10px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; font-size: 13px; margin-bottom: 14px;"></div>

            <div class="inpage-feedback-footer">
                <button type="submit" id="inpage-fb-submit-btn" class="feedback-submit-btn">
                    <span>Tuma Wazo / Maoni</span>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </div>
        </form>

        {{-- Success State --}}
        <div id="inpage-feedback-success" class="feedback-success" style="display: none; text-align: center; padding: 24px 12px;">
            <div class="feedback-success-icon" aria-hidden="true" style="width: 52px; height: 52px; border-radius: 50%; background: rgba(16, 185, 129, 0.15); border: 1.5px solid #10b981; color: #10b981; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h4 class="feedback-success-title" style="font-size: 20px; font-weight: 800; color: var(--text-primary); margin: 0 0 8px;">Shukran Sana!</h4>
            <p class="feedback-success-desc" style="font-size: 14px; color: var(--text-secondary); max-width: 480px; margin: 0 auto 16px; line-height: 1.55;">
                Wazo na maoni yako yamepokelewa vizuri. Timu yetu itayafanyia kazi kwa ajili ya kuboresha Bayt Ul Hadith.
            </p>
            <button type="button" class="feedback-chip" style="margin: 0 auto; display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;" onclick="resetInpageFeedback()">
                <span>Tuma Maoni Mengine</span>
            </button>
        </div>
    </div>
</section>

<script>
    function selectInpageFeedbackCat(cat, el) {
        const catInput = document.getElementById('inpage-fb-category');
        if (catInput) catInput.value = cat;
        document.querySelectorAll('#fomu-ya-maoni .feedback-chip').forEach(btn => btn.classList.remove('active'));
        if (el) el.classList.add('active');
    }

    function resetInpageFeedback() {
        const form = document.getElementById('inpage-feedback-form');
        const successBox = document.getElementById('inpage-feedback-success');
        if (form && successBox) {
            form.reset();
            form.style.display = 'block';
            successBox.style.display = 'none';
        }
    }

    async function submitInpageFeedback(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('inpage-fb-submit-btn');
        const errorDiv = document.getElementById('inpage-fb-error-msg');
        const form = document.getElementById('inpage-feedback-form');
        const successBox = document.getElementById('inpage-feedback-success');

        if (errorDiv) errorDiv.style.display = 'none';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.dataset.original = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span>Inatuma...</span>';
        }

        const payload = {
            category: document.getElementById('inpage-fb-category')?.value || 'feature',
            message: document.getElementById('inpage-fb-message')?.value || '',
            name: document.getElementById('inpage-fb-name')?.value || '',
            contact: document.getElementById('inpage-fb-contact')?.value || '',
            page_url: window.location.href,
            hadith_ref: null
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
                if (form) form.style.display = 'none';
                if (successBox) successBox.style.display = 'block';
            } else {
                if (errorDiv) {
                    errorDiv.textContent = data.message || 'Hitilafu imetokea wakati wa kutuma. Tafadhali jaribu tena.';
                    errorDiv.style.display = 'block';
                }
            }
        } catch (_) {
            if (errorDiv) {
                errorDiv.textContent = 'Hitilafu ya muunganisho wa mtandao. Tafadhali jaribu tena.';
                errorDiv.style.display = 'block';
            }
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                if (submitBtn.dataset.original) submitBtn.innerHTML = submitBtn.dataset.original;
            }
        }
    }
</script>
