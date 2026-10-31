<x-layouts.app title="Majaribio ya Malipo · Bayt Ul Hadith">
    <div class="support-page-shell" style="max-width: 680px; margin: 2rem auto; padding: 0 1rem;">
        
        {{-- Kadi ya Mwongozo wa Sandbox / Majaribio --}}
        <div style="background: rgba(226, 232, 240, 0.08); border: 1.5px solid var(--border-color); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; text-align: center;">
            <span style="display: inline-block; font-size: 0.75rem; font-weight: 800; letter-spacing: 1.5px; color: var(--gold); padding: 4px 12px; background: var(--gold-subtle); border-radius: 999px; margin-bottom: 0.75rem;">
                MAJARIBIO YA MALIPO (PESAPAL SANDBOX SIMULATOR)
            </span>
            <h1 style="font-size: 1.4rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.5rem;">
                Thibitisha Malipo ya Jaribio
            </h1>
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin: 0; line-height: 1.6;">
                Hali ya majaribio (Simulation Mode) ipo tayari. Unaweza kujaribu mtiririko kamili wa malipo ya mitandao ya simu na kadi. Pindi utakapoingiza funguo zako za Pesapal kwenye faili la <code>.env</code>, ukurasa huu utabadilika moja kwa moja na kuwa ukurasa rasmi wa Pesapal.
            </p>
        </div>

        {{-- Muhtasari wa Ankara --}}
        <div class="support-card" style="margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-subtle); padding-bottom: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Kumbukumbu Namba</span>
                    <strong style="font-family: monospace; font-size: 1rem; color: var(--gold);">{{ $payment->reference }}</strong>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Kiasi Kinacholipwa</span>
                    <strong style="font-size: 1.35rem; color: var(--text-primary);">
                        {{ $payment->currency === 'TZS' ? 'TZS ' . number_format($payment->amount) : '$' . number_format($payment->amount, 2) }}
                    </strong>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; font-size: 0.9rem;">
                <div>
                    <span style="color: var(--text-muted); display: block;">Aina ya Mchango:</span>
                    <strong style="color: var(--text-primary);">
                        {{ $payment->purpose === 'supporter_membership' ? 'Mwanachama Msaidizi (Supporter)' : 'Sadaka Jariya' }}
                    </strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block;">Mchangiaji:</span>
                    <strong style="color: var(--text-primary);">
                        {{ $payment->donor_name ?: 'Mchangiaji Mkarimu (Bila Jina)' }}
                    </strong>
                </div>
            </div>

            {{-- Chagua Mtandao wa Majaribio --}}
            <label class="support-label" style="margin-bottom: 0.75rem;">Chagua Njia ya Majaribio ya Kulipa:</label>
            <form action="{{ route('payments.callback') }}" method="GET" id="simulation-form">
                <input type="hidden" name="reference" value="{{ $payment->reference }}">
                <input type="hidden" name="OrderTrackingId" value="{{ $payment->gateway_reference }}">
                <input type="hidden" name="simulation_action" id="simulation-action" value="complete">

                <div class="payment-methods-grid" style="margin-bottom: 1.5rem;">
                    <label class="payment-method-card active" style="cursor: pointer;">
                        <input type="radio" name="method" value="mpesa" checked class="pm-radio">
                        <div class="pm-meta">
                            <strong>M-Pesa (Vodacom)</strong>
                            <span>Malipo ya Simu (Tanzania)</span>
                        </div>
                    </label>

                    <label class="payment-method-card" style="cursor: pointer;">
                        <input type="radio" name="method" value="tigopesa" class="pm-radio">
                        <div class="pm-meta">
                            <strong>Tigo Pesa (Mixx by Yas)</strong>
                            <span>Malipo ya Simu (Tanzania)</span>
                        </div>
                    </label>

                    <label class="payment-method-card" style="cursor: pointer;">
                        <input type="radio" name="method" value="airtelmoney" class="pm-radio">
                        <div class="pm-meta">
                            <strong>Airtel Money</strong>
                            <span>Malipo ya Simu (Tanzania)</span>
                        </div>
                    </label>

                    <label class="payment-method-card" style="cursor: pointer;">
                        <input type="radio" name="method" value="card" class="pm-radio">
                        <div class="pm-meta">
                            <strong>Kadi ya Benki</strong>
                            <span>Visa / Mastercard (Kimataifa)</span>
                        </div>
                    </label>
                </div>

                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <button type="button" onclick="document.getElementById('simulation-action').value='cancel'; document.getElementById('simulation-form').submit();" class="preset-chip-btn" style="flex: 1; padding: 0.85rem;">
                        Ghairi Malipo
                    </button>
                    <button type="submit" class="support-submit-btn" style="flex: 2; margin: 0;">
                        <span>Kamilisha Malipo ya Jaribio</span>
                        <span aria-hidden="true">✓</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-layouts.app>
