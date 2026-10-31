<x-layouts.app title="Risiti ya Mchango · Bayt Ul Hadith">
    <div class="support-page-shell" style="max-width: 680px; margin: 2rem auto; padding: 0 1rem;">
        
        {{-- Kadi Kuu ya Risiti --}}
        <article class="support-card" id="receipt-printable" style="padding: 2.25rem 2rem; position: relative; overflow: hidden;">
            
            {{-- Hali ya Muamala (Status Badge) --}}
            <div style="text-align: center; margin-bottom: 2rem;">
                @if ($payment->status === 'completed')
                    <div style="width: 58px; height: 58px; margin: 0 auto 1rem; border-radius: 50%; background: rgba(16, 185, 129, 0.15); border: 2px solid var(--emerald); display: flex; align-items: center; justify-content: center; color: var(--emerald); font-size: 1.6rem; font-weight: 800;">
                        ✓
                    </div>
                    <span style="display: inline-block; font-size: 0.75rem; font-weight: 800; letter-spacing: 2px; color: var(--emerald); padding: 4px 14px; background: rgba(16, 185, 129, 0.1); border-radius: 999px; margin-bottom: 0.5rem;">
                        MALIPO YAMETHIBITISHWA
                    </span>
                    <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.5rem;">
                        Jazakallahu Khayran!
                    </h1>
                    <p style="font-size: 0.95rem; color: var(--text-secondary); margin: 0 auto; max-width: 480px; line-height: 1.6;">
                        Mchango wako umepokelewa kikamilifu. Allah awalipe kheri, baraka, na akubaliwe kuwa Sadaka Jariya yenye thawabu zisizokatika.
                    </p>
                @elseif ($payment->status === 'failed')
                    <div style="width: 58px; height: 58px; margin: 0 auto 1rem; border-radius: 50%; background: rgba(239, 68, 68, 0.15); border: 2px solid #ef4444; display: flex; align-items: center; justify-content: center; color: #ef4444; font-size: 1.6rem; font-weight: 800;">
                        ✕
                    </div>
                    <span style="display: inline-block; font-size: 0.75rem; font-weight: 800; letter-spacing: 2px; color: #ef4444; padding: 4px 14px; background: rgba(239, 68, 68, 0.1); border-radius: 999px; margin-bottom: 0.5rem;">
                        MUAMALA HAUJATIMIA
                    </span>
                    <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.5rem;">
                        Malipo Yameshindikana au Yameghairiwa
                    </h1>
                @else
                    <div style="width: 58px; height: 58px; margin: 0 auto 1rem; border-radius: 50%; background: rgba(226, 232, 240, 0.15); border: 2px solid var(--gold); display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: 1.6rem; font-weight: 800;">
                        ⏳
                    </div>
                    <span style="display: inline-block; font-size: 0.75rem; font-weight: 800; letter-spacing: 2px; color: var(--gold); padding: 4px 14px; background: var(--gold-subtle); border-radius: 999px; margin-bottom: 0.5rem;">
                        INASUBIRI UTHIBITISHO
                    </span>
                    <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.5rem;">
                        Muamala Unachakatwa
                    </h1>
                @endif
            </div>

            {{-- Kiasi cha Risiti --}}
            <div style="background: var(--bg-surface-elevated); border: 1.5px solid var(--border-color); border-radius: 14px; padding: 1.25rem 1.5rem; text-align: center; margin-bottom: 1.75rem;">
                <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); letter-spacing: 1px; text-transform: uppercase; display: block; margin-bottom: 0.25rem;">
                    Kiasi cha Mchango
                </span>
                <span style="font-size: 2.2rem; font-weight: 800; color: var(--gold); letter-spacing: -0.5px;">
                    {{ $payment->currency === 'TZS' ? 'TZS ' . number_format($payment->amount) : '$' . number_format($payment->amount, 2) }}
                </span>
                <span style="font-size: 0.85rem; color: var(--text-secondary); display: block; margin-top: 0.25rem;">
                    {{ $payment->purpose === 'supporter_membership' ? 'Uanachama wa Supporter' : 'Sadaka Jariya' }}
                    @if ($payment->billing_cycle === 'monthly')
                        · Kila Mwezi
                    @else
                        · Mchango wa Mara Moja
                    @endif
                </span>
            </div>

            {{-- Jedwali la Maelezo ya Muamala --}}
            <div style="border-top: 1px solid var(--border-subtle); padding-top: 1.25rem; margin-bottom: 1.75rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; font-size: 0.9rem;">
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Kumbukumbu Namba:</span>
                        <strong style="color: var(--text-primary); font-family: monospace;">{{ $payment->reference }}</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Tarehe &amp; Saa:</span>
                        <strong style="color: var(--text-primary);">{{ $payment->created_at->format('d/m/Y H:i') }}</strong>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; font-size: 0.9rem;">
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Njia ya Malipo:</span>
                        <strong style="color: var(--text-primary); text-transform: uppercase;">
                            {{ $payment->payment_method ?? 'Mitandao ya Simu / Kadi' }}
                        </strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Mchangiaji:</span>
                        <strong style="color: var(--text-primary);">
                            {{ $payment->donor_name ?: 'Mchangiaji Mkarimu (Bila Jina)' }}
                        </strong>
                    </div>
                </div>

                @if ($payment->gateway_reference)
                    <div style="margin-bottom: 1rem; font-size: 0.9rem;">
                        <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Namba ya Lango (Tracking ID):</span>
                        <span style="color: var(--text-secondary); font-family: monospace; font-size: 0.85rem;">{{ $payment->gateway_reference }}</span>
                    </div>
                @endif

                @if ($payment->message)
                    <div style="background: rgba(226, 232, 240, 0.05); border: 1px solid rgba(226, 232, 240, 0.2); padding: 0.85rem 1rem; border-radius: 8px; margin-top: 1rem;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: var(--gold); letter-spacing: 1px; display: block; margin-bottom: 0.25rem;">
                            DUA / UJUMBE WA MOYONI:
                        </span>
                        <p style="margin: 0; font-size: 0.9rem; font-style: italic; color: var(--text-primary); line-height: 1.5;">
                            "{{ $payment->message }}"
                        </p>
                    </div>
                @endif
            </div>

            {{-- Aya ya Sadaka --}}
            <div style="background: var(--arabic-bg); border: 1px solid var(--arabic-border); border-radius: 12px; padding: 1.25rem; text-align: right; margin-bottom: 2rem;">
                <p class="arabic" lang="ar" dir="rtl" style="font-size: 1.35rem; line-height: 2.1; margin: 0 0 0.5rem; text-align: right;">
                    مَّثَلُ الَّذِينَ يُنفِقُونَ أَمْوَالَهُمْ فِي سَبِيلِ اللَّهِ كَمَثَلِ حَبَّةٍ أَنبَتَتْ سَبْعَ سَنَابِلَ فِي كُلِّ سُنبُلَةٍ مِّائَةُ حَبَّةٍ
                </p>
                <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 0; text-align: left; direction: ltr;">
                    "Mfano wa wale wanaotoa mali zao katika njia ya Mwenyezi Mungu ni kama mfano wa mbegu iliyootesha mashuke saba, katika kila shuke mna chembe mia moja..." (Qur'an 2:261)
                </p>
            </div>

            {{-- Vitufe vya Vitendo --}}
            <div class="no-print" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="{{ route('library') }}" class="preset-chip-btn" style="flex: 1; text-align: center; text-decoration: none;">
                    Rudi Maktaba
                </a>
                <a href="{{ route('support') }}" class="preset-chip-btn" style="flex: 1; text-align: center; text-decoration: none;">
                    Changia Tena
                </a>
                <button type="button" onclick="window.print()" class="support-submit-btn" style="flex: 1.2; margin: 0;">
                    Chapisha Risiti
                </button>
            </div>

        </article>

    </div>
</x-layouts.app>
