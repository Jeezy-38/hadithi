<x-layouts.app :title="'Admin Panel — Jopo la Utawala'">
    <div class="admin-shell" style="max-width: 1200px; margin: 0 auto; padding: 24px 16px 80px;">
        
        {{-- 1. Top Admin Bar --}}
        <div class="admin-header-row" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 28px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color);">
            <div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: var(--emerald-subtle); color: var(--emerald); font-size: 18px;">⚙️</span>
                    <h1 style="font-size: 26px; font-weight: 800; margin: 0; color: var(--text-primary); letter-spacing: -0.5px;">Jopo Kuu la Utawala (Admin)</h1>
                </div>
                <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13.5px;">Ufuatiliaji wa watazamaji, mapato ya sadaka na usimamizi wa mfumo.</p>
            </div>

            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px; background: var(--emerald-subtle); color: var(--emerald); font-size: 12.5px; font-weight: 700;">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: var(--emerald);"></span>
                    Admin: {{ auth()->user()->name }}
                </span>
                <a href="{{ route('library') }}" class="read-btn" style="text-decoration: none;">
                    <span>Rudi Kwenye Tovuti</span>
                    <span aria-hidden="true">↗</span>
                </a>
            </div>
        </div>

        {{-- 2. KPI Overview Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 32px;">
            
            {{-- Watazamaji Leo --}}
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Watazamaji Leo</span>
                    <span style="font-size: 18px;">👁️</span>
                </div>
                <div style="font-size: 32px; font-weight: 900; color: var(--text-primary); line-height: 1.1; margin-bottom: 6px;">
                    {{ number_format($viewsToday) }}
                </div>
                <div style="font-size: 12.5px; color: var(--text-muted);">
                    Watu wa kipekee (Unique): <strong style="color: var(--emerald);">{{ number_format($uniqueToday) }}</strong>
                </div>
            </div>

            {{-- Watazamaji Siku 7 --}}
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Siku 7 Zilizopita</span>
                    <span style="font-size: 18px;">📈</span>
                </div>
                <div style="font-size: 32px; font-weight: 900; color: var(--text-primary); line-height: 1.1; margin-bottom: 6px;">
                    {{ number_format($views7Days) }}
                </div>
                <div style="font-size: 12.5px; color: var(--text-muted);">
                    Watu wa kipekee (Unique): <strong style="color: var(--emerald);">{{ number_format($unique7Days) }}</strong>
                </div>
            </div>

            {{-- Watazamaji Siku 30 --}}
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Siku 30 Zilizopita</span>
                    <span style="font-size: 18px;">🗓️</span>
                </div>
                <div style="font-size: 32px; font-weight: 900; color: var(--text-primary); line-height: 1.1; margin-bottom: 6px;">
                    {{ number_format($views30Days) }}
                </div>
                <div style="font-size: 12.5px; color: var(--text-muted);">
                    Jumla tangu mwanzo: <strong style="color: var(--text-secondary);">{{ number_format($viewsAllTime) }}</strong>
                </div>
            </div>

            {{-- Sadaka Zilizolipwa (Mapato) --}}
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Jumla ya Sadaka Zilizolipwa</span>
                    <span style="font-size: 18px;">💳</span>
                </div>
                <div style="font-size: 26px; font-weight: 900; color: var(--emerald); line-height: 1.1; margin-bottom: 4px;">
                    {{ number_format($totalTzs) }} <span style="font-size: 14px; font-weight: 700;">TZS</span>
                </div>
                @if($totalUsd > 0)
                    <div style="font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 6px;">
                        + ${{ number_format($totalUsd, 2) }} USD
                    </div>
                @endif
                <div style="font-size: 12.5px; color: var(--text-muted);">
                    Mwezi huu: <strong style="color: var(--text-secondary);">{{ number_format($monthTzs) }} TZS</strong>
                </div>
            </div>

        </div>

        {{-- 3. Traffic Details Grid (Visual Daily Stats & Top Pages & Devices) --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 20px; margin-bottom: 36px;">
            
            {{-- Chati ya Ziara za Kila Siku (Siku 7) --}}
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 18px; padding: 24px; box-shadow: var(--card-shadow);">
                <h2 style="font-size: 17px; font-weight: 800; margin: 0 0 16px; color: var(--text-primary); display: flex; align-items: center; justify-content: space-between;">
                    <span>📊 Mwelekeo wa Ziara (Siku 7)</span>
                    <span style="font-size: 12px; font-weight: 600; color: var(--text-muted);">Kila Siku</span>
                </h2>

                @php
                    $maxDaily = max(array_column($dailyStats, 'count')) ?: 1;
                @endphp

                <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 8px; height: 160px; padding: 12px 4px 4px; border-bottom: 1px solid var(--border-subtle);">
                    @foreach($dailyStats as $day)
                        @php
                            $heightPercent = max(6, round(($day['count'] / $maxDaily) * 100));
                        @endphp
                        <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; gap: 6px;">
                            <span style="font-size: 11px; font-weight: 700; color: var(--emerald);">{{ $day['count'] }}</span>
                            <div style="width: 100%; max-width: 32px; height: {{ $heightPercent }}%; background: linear-gradient(180deg, var(--emerald) 0%, rgba(16, 185, 129, 0.4) 100%); border-radius: 6px 6px 2px 2px; transition: height 0.3s ease;"></div>
                            <span style="font-size: 10.5px; font-weight: 600; color: var(--text-muted); text-align: center; white-space: nowrap;">{{ $day['label'] }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Device Distribution bar --}}
                <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-subtle);">
                    <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 700; margin-bottom: 8px;">
                        <span>📱 Simu (Mobile): {{ $mobilePercent }}%</span>
                        <span>💻 Kompyuta (Desktop): {{ $desktopPercent }}%</span>
                    </div>
                    <div style="width: 100%; height: 8px; background: var(--border-subtle); border-radius: 4px; overflow: hidden; display: flex;">
                        <div style="width: {{ $mobilePercent }}%; background: var(--emerald);"></div>
                        <div style="width: {{ $desktopPercent }}%; background: var(--text-dim);"></div>
                    </div>
                </div>
            </div>

            {{-- Kurasa Zinazoongoza Kutembelewa --}}
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 18px; padding: 24px; box-shadow: var(--card-shadow);">
                <h2 style="font-size: 17px; font-weight: 800; margin: 0 0 16px; color: var(--text-primary);">
                    🔥 Kurasa Maarufu (Siku 30 Zilizopita)
                </h2>

                @if($topPages->isEmpty())
                    <p style="color: var(--text-muted); font-size: 13.5px; padding: 20px 0; text-align: center;">
                        Bado hakuna data ya watazamaji iliyorekodiwa. Data itaanza kuonekana punde watazamaji wanapofungua kurasa.
                    </p>
                @else
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($topPages as $page)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 9px 12px; background: var(--bg-surface-elevated); border-radius: 10px; font-size: 13.5px;">
                                <span style="font-family: monospace; font-weight: 600; color: var(--text-primary); word-break: break-all; max-width: 75%;">
                                    {{ $page->path }}
                                </span>
                                <span style="font-weight: 800; color: var(--emerald); background: var(--emerald-subtle); padding: 3px 8px; border-radius: 12px; font-size: 12px;">
                                    {{ number_format($page->total) }} views
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- 4. Malipo na Sadaka Tracker Table --}}
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 18px; padding: 24px; box-shadow: var(--card-shadow); margin-bottom: 32px;">
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 20px;">
                <div>
                    <h2 style="font-size: 20px; font-weight: 800; margin: 0; color: var(--text-primary);">
                        💳 Usimamizi wa Malipo & Sadaka
                    </h2>
                    <p style="margin: 4px 0 0; color: var(--text-muted); font-size: 13px;">
                        Miamala yote ya michango na sadaka zilizofanyika.
                    </p>
                </div>

                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.payments.export') }}" class="read-btn" style="text-decoration: none;">
                        <span>📥 Pakua Ripoti (CSV)</span>
                    </a>
                </div>
            </div>

            {{-- Filters na Search Form --}}
            <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; align-items: center;">
                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                    <a href="{{ route('admin.dashboard', ['status' => 'all', 'search' => $paymentSearch]) }}" 
                       style="padding: 7px 14px; border-radius: 20px; text-decoration: none; font-size: 13px; font-weight: 700; transition: all 0.2s; {{ $paymentStatus === 'all' ? 'background: var(--emerald); color: #fff;' : 'background: var(--bg-surface-elevated); color: var(--text-muted); border: 1px solid var(--border-color);' }}">
                        Zote ({{ $paymentsCompletedCount + $paymentsPendingCount + $paymentsFailedCount }})
                    </a>
                    <a href="{{ route('admin.dashboard', ['status' => 'completed', 'search' => $paymentSearch]) }}" 
                       style="padding: 7px 14px; border-radius: 20px; text-decoration: none; font-size: 13px; font-weight: 700; transition: all 0.2s; {{ $paymentStatus === 'completed' ? 'background: var(--emerald); color: #fff;' : 'background: var(--bg-surface-elevated); color: var(--text-muted); border: 1px solid var(--border-color);' }}">
                        Zilizofanikiwa ({{ $paymentsCompletedCount }})
                    </a>
                    <a href="{{ route('admin.dashboard', ['status' => 'pending', 'search' => $paymentSearch]) }}" 
                       style="padding: 7px 14px; border-radius: 20px; text-decoration: none; font-size: 13px; font-weight: 700; transition: all 0.2s; {{ $paymentStatus === 'pending' ? 'background: var(--emerald); color: #fff;' : 'background: var(--bg-surface-elevated); color: var(--text-muted); border: 1px solid var(--border-color);' }}">
                        Zinazosubiri ({{ $paymentsPendingCount }})
                    </a>
                    <a href="{{ route('admin.dashboard', ['status' => 'failed', 'search' => $paymentSearch]) }}" 
                       style="padding: 7px 14px; border-radius: 20px; text-decoration: none; font-size: 13px; font-weight: 700; transition: all 0.2s; {{ $paymentStatus === 'failed' ? 'background: var(--emerald); color: #fff;' : 'background: var(--bg-surface-elevated); color: var(--text-muted); border: 1px solid var(--border-color);' }}">
                        Zilizofeli ({{ $paymentsFailedCount }})
                    </a>
                </div>

                <div style="flex: 1; min-width: 220px; display: flex; gap: 8px;">
                    <input type="text" name="search" value="{{ $paymentSearch }}" placeholder="Tafuta jina, simu, au reference..." 
                           style="flex: 1; padding: 8px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-surface-elevated); color: var(--text-primary); font-size: 13.5px; outline: none;">
                    <button type="submit" class="read-btn" style="border: none; cursor: pointer;">
                        <span>Tafuta</span>
                    </button>
                    @if($paymentSearch !== '' || $paymentStatus !== 'all')
                        <a href="{{ route('admin.dashboard') }}" style="display: inline-flex; align-items: center; padding: 0 10px; font-size: 13px; color: var(--text-muted); text-decoration: none;">Weka Upya ×</a>
                    @endif
                </div>
            </form>

            {{-- Table of Payments --}}
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13.5px; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-color); color: var(--text-muted); font-size: 12px; text-transform: uppercase;">
                            <th style="padding: 12px 10px;">Reference</th>
                            <th style="padding: 12px 10px;">Mchangiaji</th>
                            <th style="padding: 12px 10px;">Kiasi</th>
                            <th style="padding: 12px 10px;">Njia</th>
                            <th style="padding: 12px 10px;">Hali</th>
                            <th style="padding: 12px 10px;">Tarehe</th>
                            <th style="padding: 12px 10px;">Ujumbe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $p)
                            <tr style="border-bottom: 1px solid var(--border-subtle); transition: background 0.15s ease;">
                                <td style="padding: 14px 10px; font-family: monospace; font-weight: 700; color: var(--text-primary);">
                                    {{ $p->reference }}
                                </td>
                                <td style="padding: 14px 10px;">
                                    <div style="font-weight: 700; color: var(--text-primary);">
                                        {{ $p->is_anonymous ? 'Binafsi (Anonymous)' : ($p->donor_name ?: 'Bila Jina') }}
                                    </div>
                                    <div style="font-size: 11.5px; color: var(--text-muted);">
                                        {{ $p->donor_phone ?: ($p->donor_email ?: '-') }}
                                    </div>
                                </td>
                                <td style="padding: 14px 10px; font-weight: 800; color: var(--text-primary); white-space: nowrap;">
                                    {{ number_format($p->amount, 0) }} {{ $p->currency }}
                                </td>
                                <td style="padding: 14px 10px; color: var(--text-muted); text-transform: uppercase; font-size: 12px;">
                                    {{ $p->payment_method ?: ($p->gateway ?: 'Pesapal') }}
                                </td>
                                <td style="padding: 14px 10px;">
                                    @if($p->status === 'completed')
                                        <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; background: rgba(16, 185, 129, 0.15); color: #10b981; font-weight: 800; font-size: 11.5px;">
                                            ✓ Imelipwa
                                        </span>
                                    @elseif($p->status === 'pending')
                                        <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; background: rgba(245, 158, 11, 0.15); color: #f59e0b; font-weight: 800; font-size: 11.5px;">
                                            ⏳ Inasubiri
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; background: rgba(239, 68, 68, 0.15); color: #ef4444; font-weight: 800; font-size: 11.5px;">
                                            ✕ Imefeli
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 14px 10px; color: var(--text-muted); font-size: 12px; white-space: nowrap;">
                                    {{ $p->created_at ? $p->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td style="padding: 14px 10px; color: var(--text-muted); font-size: 12px; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $p->message ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 30px; text-align: center; color: var(--text-muted);">
                                    Hakuna taarifa yoyote ya malipo iliyopatikana kwenye kigezo hiki.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if($payments->hasPages())
                <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-subtle);">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

        {{-- 5. System Content Overview --}}
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 18px; padding: 24px; box-shadow: var(--card-shadow);">
            <h2 style="font-size: 18px; font-weight: 800; margin: 0 0 16px; color: var(--text-primary);">
                📚 Muhtasari wa Maudhui na Watumiaji
            </h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;">
                <div style="padding: 14px; background: var(--bg-surface-elevated); border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--text-muted);">Hadithi Zote</div>
                    <div style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-top: 4px;">{{ number_format($totalHadith) }}</div>
                </div>
                <div style="padding: 14px; background: var(--bg-surface-elevated); border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--text-muted);">Aya za Qur'ani</div>
                    <div style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-top: 4px;">{{ number_format($totalAyahs) }}</div>
                </div>
                <div style="padding: 14px; background: var(--bg-surface-elevated); border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--text-muted);">Dua & Adhkar</div>
                    <div style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-top: 4px;">{{ number_format($totalDuas) }}</div>
                </div>
                <div style="padding: 14px; background: var(--bg-surface-elevated); border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--text-muted);">Watumiaji Waliosajiliwa</div>
                    <div style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-top: 4px;">{{ number_format($totalUsers) }}</div>
                </div>
                <div style="padding: 14px; background: var(--bg-surface-elevated); border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--text-muted);">Wafadhili (Supporters)</div>
                    <div style="font-size: 22px; font-weight: 800; color: var(--emerald); margin-top: 4px;">{{ number_format($totalSupporters) }}</div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
