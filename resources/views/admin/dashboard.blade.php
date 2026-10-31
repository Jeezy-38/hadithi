<x-layouts.app :title="'Admin Panel — Jopo Kuu la Utawala'">
    <div class="admin-dashboard-root" style="max-width: 1320px; margin: 0 auto; padding: 24px 16px 90px;">

        <style>
            /* Ocean Blue & Gold — follows the site theme (data-theme) */
            :root {
                --adm-emerald: #0a3f66;
                --adm-emerald-glow: #165a8a;
                --adm-emerald-subtle: rgba(10, 63, 102, 0.09);
                --adm-gold: #a97c12;
                --adm-gold-glow: #c09828;
                --adm-gold-subtle: rgba(192, 152, 40, 0.14);
                --adm-blue: #0e6aa8;
                --adm-blue-glow: #38a0d8;
                --adm-blue-subtle: rgba(14, 106, 168, 0.10);
                --adm-purple: #6d4fc2;
                --adm-purple-subtle: rgba(109, 79, 194, 0.10);
                --adm-card-bg: #ffffff;
                --adm-card-border: #cfdfeb;
                --adm-card-shadow: 0 4px 20px -2px rgba(7, 41, 63, 0.08), 0 1px 3px rgba(0, 0, 0, 0.03);
                --adm-text-main: #07293f;
                --adm-text-sub: #4b6a80;
                --adm-hover-bg: #eaf2f8;
                --adm-ring: rgba(10, 63, 102, 0.30);
            }

            [data-theme="dark"] {
                --adm-emerald: #e0c868;
                --adm-emerald-glow: #f0dc8c;
                --adm-emerald-subtle: rgba(224, 200, 104, 0.14);
                --adm-gold: #e0c868;
                --adm-gold-glow: #f0dc8c;
                --adm-gold-subtle: rgba(224, 200, 104, 0.14);
                --adm-blue: #5db8ee;
                --adm-blue-glow: #8ed0f5;
                --adm-blue-subtle: rgba(93, 184, 238, 0.16);
                --adm-purple: #a78bfa;
                --adm-purple-subtle: rgba(167, 139, 250, 0.16);
                --adm-card-bg: #0a3552;
                --adm-card-border: rgba(224, 200, 104, 0.16);
                --adm-card-shadow: 0 8px 30px -4px rgba(0, 0, 0, 0.4);
                --adm-text-main: #f2f7fb;
                --adm-text-sub: #9db8cb;
                --adm-hover-bg: #124666;
                --adm-ring: rgba(224, 200, 104, 0.35);
            }

            .adm-tab-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 18px;
                border-radius: 12px;
                font-size: 13.5px;
                font-weight: 700;
                text-decoration: none;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                color: var(--adm-text-sub);
                border: 1px solid transparent;
            }
            .adm-tab-btn:hover {
                color: var(--adm-text-main);
                background: var(--adm-hover-bg);
            }
            .adm-tab-btn.active {
                background: var(--adm-emerald-subtle);
                color: var(--adm-emerald);
                border-color: var(--adm-ring);
                box-shadow: 0 2px 8px -2px var(--adm-ring);
            }

            .adm-kpi-card {
                background: var(--adm-card-bg);
                border: 1px solid var(--adm-card-border);
                border-radius: 18px;
                padding: 22px;
                box-shadow: var(--adm-card-shadow);
                position: relative;
                overflow: hidden;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .adm-kpi-card:hover {
                transform: translateY(-2px);
            }

            .adm-badge {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 11.5px;
                font-weight: 700;
                line-height: 1;
            }

            .adm-badge-emerald { background: var(--adm-emerald-subtle); color: var(--adm-emerald); border: 1px solid var(--adm-ring); }
            .adm-badge-gold { background: var(--adm-gold-subtle); color: var(--adm-gold); border: 1px solid rgba(245, 158, 11, 0.25); }
            .adm-badge-blue { background: var(--adm-blue-subtle); color: var(--adm-blue); border: 1px solid rgba(56, 189, 248, 0.25); }
            .adm-badge-purple { background: var(--adm-purple-subtle); color: var(--adm-purple); border: 1px solid rgba(167, 139, 250, 0.25); }
            .adm-badge-gray { background: rgba(148, 163, 184, 0.12); color: var(--adm-text-sub); border: 1px solid rgba(148, 163, 184, 0.2); }
            .adm-badge-red { background: rgba(239, 68, 68, 0.12); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

            .adm-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                font-size: 13.5px;
            }
            .adm-table th {
                text-align: left;
                padding: 13px 16px;
                font-size: 11.5px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.6px;
                color: var(--adm-text-sub);
                border-bottom: 1px solid var(--adm-card-border);
                background: rgba(0, 0, 0, 0.02);
            }
            [data-theme="dark"] .adm-table th {
                background: rgba(255, 255, 255, 0.02);
            }
            .adm-table td {
                padding: 15px 16px;
                border-bottom: 1px solid var(--adm-card-border);
                color: var(--adm-text-main);
                vertical-align: middle;
            }
            .adm-table tr:last-child td {
                border-bottom: none;
            }
            .adm-table tr:hover td {
                background: var(--adm-hover-bg);
            }

            .adm-action-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 7px 13px;
                border-radius: 9px;
                font-size: 12.5px;
                font-weight: 700;
                border: 1px solid transparent;
                cursor: pointer;
                transition: all 0.15s ease;
                background: transparent;
            }
            .adm-action-btn:hover {
                transform: scale(1.02);
            }

            /* Responsive: tablet & phone (360px) */
            .admin-dashboard-root { overflow-x: hidden; }
            .admin-dashboard-root img { max-width: 100%; }
            @media (max-width: 768px) {
                .admin-dashboard-root { padding: 14px 10px 90px !important; }
                .adm-tab-btn { padding: 8px 12px; font-size: 12.5px; }
                .adm-kpi-card { padding: 16px; border-radius: 14px; }
                .adm-table th, .adm-table td { padding: 10px 10px; }
                .admin-dashboard-root table { display: block; overflow-x: auto; -webkit-overflow-scrolling: touch; white-space: nowrap; }
                .admin-dashboard-root div[style*="grid-template-columns"] { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)) !important; gap: 12px !important; }
            }
            @media (max-width: 480px) {
                .admin-dashboard-root div[style*="minmax(360px"],
                .admin-dashboard-root div[style*="minmax(260px"] { grid-template-columns: 1fr !important; }
                .admin-dashboard-root [style*="flex-wrap"] { gap: 8px !important; }
                .admin-dashboard-root h1 { font-size: 22px !important; }
                .adm-action-btn { padding: 6px 10px; font-size: 12px; }
            }
        </style>

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div style="background: var(--adm-emerald-subtle); border: 1px solid var(--adm-ring); border-radius: 14px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; color: var(--adm-emerald); font-size: 14px; font-weight: 700;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 18px;">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: transparent; border: none; color: inherit; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 14px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; color: #ef4444; font-size: 14px; font-weight: 700;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 18px;">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: transparent; border: none; color: inherit; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
        @endif

        {{-- Top Header Banner --}}
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--adm-card-border);">
            <div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, var(--adm-emerald), var(--adm-emerald-glow)); color: #fff; font-size: 20px; box-shadow: 0 4px 14px var(--adm-ring);">
                        🛡️
                    </span>
                    <div>
                        <h1 style="font-size: 27px; font-weight: 900; margin: 0; color: var(--adm-text-main); letter-spacing: -0.6px; line-height: 1.2;">
                            Jopo Kuu la Utawala (Super Admin)
                        </h1>
                        <p style="margin: 3px 0 0; color: var(--adm-text-sub); font-size: 13.5px; font-weight: 500;">
                            Usimamizi kamili wa watumiaji, maudhui, takwimu za watazamaji na malipo ya Sadaka.
                        </p>
                    </div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span class="adm-badge adm-badge-emerald" style="padding: 6px 14px; font-size: 12.5px;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--adm-emerald); box-shadow: 0 0 8px var(--adm-emerald);"></span>
                    Msimamizi: {{ auth()->user()->name }}
                </span>
                
                <form action="{{ route('admin.system.clear-cache') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="adm-action-btn adm-badge-gold" style="padding: 7px 14px; border: 1px solid rgba(245, 158, 11, 0.3);" title="Safisha cache za mfumo">
                        <span>⚡ Safisha Cache</span>
                    </button>
                </form>

                <a href="{{ route('library') }}" class="read-btn" style="text-decoration: none; padding: 7px 16px; font-size: 13px;">
                    <span>Tovuti Kuu</span>
                    <span aria-hidden="true">↗</span>
                </a>
            </div>
        </div>

        {{-- Modular Navigation Tabs (Pills) --}}
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 28px; overflow-x: auto; padding-bottom: 6px; -webkit-overflow-scrolling: touch;">
            <a href="{{ route('admin.dashboard', ['tab' => 'overview']) }}" class="adm-tab-btn {{ $activeTab === 'overview' ? 'active' : '' }}">
                <span>📈</span>
                <span>Trafiki & Muhtasari</span>
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="adm-tab-btn {{ $activeTab === 'users' ? 'active' : '' }}">
                <span>👥</span>
                <span>Usimamizi wa Watumiaji</span>
                <span class="adm-badge adm-badge-blue" style="font-size: 11px; padding: 2px 7px;">{{ number_format($totalUsers) }}</span>
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'payments']) }}" class="adm-tab-btn {{ $activeTab === 'payments' ? 'active' : '' }}">
                <span>💳</span>
                <span>Malipo & Sadaka</span>
                <span class="adm-badge adm-badge-emerald" style="font-size: 11px; padding: 2px 7px;">{{ number_format($paymentsCompletedCount) }}</span>
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'content']) }}" class="adm-tab-btn {{ $activeTab === 'content' ? 'active' : '' }}">
                <span>📖</span>
                <span>Maudhui (Hadithi & Dua)</span>
                <span class="adm-badge adm-badge-purple" style="font-size: 11px; padding: 2px 7px;">{{ number_format($totalHadith) }}</span>
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'system']) }}" class="adm-tab-btn {{ $activeTab === 'system' ? 'active' : '' }}">
                <span>⚙️</span>
                <span>Mfumo & ClickPesa</span>
            </a>
        </div>

        {{-- ========================================================================= --}}
        {{-- TAB 1: OVERVIEW & TRAFFIC --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'overview')
            
            {{-- KPI Summary Grid --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 30px;">
                {{-- Watazamaji Leo --}}
                <div class="adm-kpi-card" style="border-top: 4px solid var(--adm-emerald);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; color: var(--adm-text-sub); letter-spacing: 0.5px;">Watazamaji Leo</span>
                        <span style="font-size: 20px;">👁️</span>
                    </div>
                    <div style="font-size: 34px; font-weight: 900; color: var(--adm-text-main); line-height: 1.1; margin-bottom: 6px;">
                        {{ number_format($viewsToday) }}
                    </div>
                    <div style="font-size: 13px; color: var(--adm-text-sub);">
                        Wageni wa Kipekee: <strong style="color: var(--adm-emerald);">{{ number_format($uniqueToday) }}</strong>
                    </div>
                </div>

                {{-- Watazamaji Siku 7 --}}
                <div class="adm-kpi-card" style="border-top: 4px solid var(--adm-blue);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; color: var(--adm-text-sub); letter-spacing: 0.5px;">Siku 7 Zilizopita</span>
                        <span style="font-size: 20px;">📊</span>
                    </div>
                    <div style="font-size: 34px; font-weight: 900; color: var(--adm-text-main); line-height: 1.1; margin-bottom: 6px;">
                        {{ number_format($views7Days) }}
                    </div>
                    <div style="font-size: 13px; color: var(--adm-text-sub);">
                        Wageni (jumla ya kila siku): <strong style="color: var(--adm-blue);">{{ number_format($unique7Days) }}</strong>
                    </div>
                </div>

                {{-- Sadaka Zilizolipwa --}}
                <div class="adm-kpi-card" style="border-top: 4px solid var(--adm-gold);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; color: var(--adm-text-sub); letter-spacing: 0.5px;">Mapato ya Sadaka</span>
                        <span style="font-size: 20px;">💰</span>
                    </div>
                    <div style="font-size: 28px; font-weight: 900; color: var(--adm-gold); line-height: 1.1; margin-bottom: 4px;">
                        {{ number_format($totalTzs) }} <span style="font-size: 14px; font-weight: 700;">TZS</span>
                    </div>
                    @if($totalUsd > 0)
                        <div style="font-size: 14px; font-weight: 800; color: var(--adm-text-main); margin-bottom: 4px;">
                            + ${{ number_format($totalUsd, 2) }} USD
                        </div>
                    @endif
                    <div style="font-size: 13px; color: var(--adm-text-sub);">
                        Mwezi Huu: <strong style="color: var(--adm-text-main);">{{ number_format($monthTzs) }} TZS</strong>
                    </div>
                </div>

                {{-- Jumla ya Watumiaji --}}
                <div class="adm-kpi-card" style="border-top: 4px solid var(--adm-purple);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; color: var(--adm-text-sub); letter-spacing: 0.5px;">Watumiaji Waliosajiliwa</span>
                        <span style="font-size: 20px;">👥</span>
                    </div>
                    <div style="font-size: 34px; font-weight: 900; color: var(--adm-text-main); line-height: 1.1; margin-bottom: 6px;">
                        {{ number_format($totalUsers) }}
                    </div>
                    <div style="font-size: 13px; color: var(--adm-text-sub);">
                        Ma-Admin: <strong style="color: var(--adm-emerald);">{{ $totalAdmins }}</strong> &bull; Supporters: <strong style="color: var(--adm-gold);">{{ $totalSupporters }}</strong>
                    </div>
                </div>
            </div>

            {{-- 7-Day Chart & Top Pages --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 20px; margin-bottom: 30px;">
                
                {{-- Mwelekeo wa Siku 7 --}}
                <div class="adm-kpi-card">
                    <h2 style="font-size: 16px; font-weight: 800; margin: 0 0 18px; color: var(--adm-text-main); display: flex; align-items: center; justify-content: space-between;">
                        <span>📊 Mwelekeo wa Watazamaji (Siku 7)</span>
                        <span class="adm-badge adm-badge-emerald">Live</span>
                    </h2>
                    @php $maxDaily = max(1, ...array_column($dailyStats, 'count')); @endphp
                    <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 10px; height: 160px; padding-top: 20px; border-bottom: 1px solid var(--adm-card-border);">
                        @foreach($dailyStats as $stat)
                            @php $height = max(12, round(($stat['count'] / $maxDaily) * 125)); @endphp
                            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <span style="font-size: 11px; font-weight: 800; color: var(--adm-emerald);">{{ $stat['count'] }}</span>
                                <div style="width: 100%; max-width: 32px; height: {{ $height }}px; border-radius: 6px 6px 0 0; background: linear-gradient(180deg, var(--adm-emerald), var(--adm-emerald-subtle));"></div>
                                <span style="font-size: 11px; color: var(--adm-text-sub); white-space: nowrap;">{{ $stat['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kurasa Zinazosomwa Zaidi --}}
                <div class="adm-kpi-card">
                    <h2 style="font-size: 16px; font-weight: 800; margin: 0 0 18px; color: var(--adm-text-main); display: flex; align-items: center; justify-content: space-between;">
                        <span>🔥 Kurasa Zinazoongoza (Siku 30)</span>
                        <span class="adm-badge adm-badge-blue">Top 7</span>
                    </h2>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @forelse($topPages as $page)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-radius: 10px; background: var(--adm-hover-bg);">
                                <code style="font-size: 12.5px; color: var(--adm-emerald); font-weight: 700; word-break: break-all;">{{ $page->path }}</code>
                                <span class="adm-badge adm-badge-emerald" style="font-size: 11.5px;">{{ number_format($page->total) }} views</span>
                            </div>
                        @empty
                            <p style="color: var(--adm-text-sub); font-size: 13px;">Bado hakuna data ya kutosha ya kurasa.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Recent Live Visitor Activity Log --}}
            <div class="adm-kpi-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div>
                        <h2 style="font-size: 16px; font-weight: 800; margin: 0; color: var(--adm-text-main);">
                            ⚡ Wageni wa Moja kwa Moja (Live Activity Feed)
                        </h2>
                        <p style="margin: 2px 0 0; font-size: 12.5px; color: var(--adm-text-sub);">
                            Ukurasa waliofungua hivi punde, kifaa na muda kamili.
                        </p>
                    </div>
                    <span class="adm-badge adm-badge-emerald">Ziara 12 za Mwisho</span>
                </div>

                <div style="overflow-x: auto;">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Ukurasa Uliofunguliwa</th>
                                <th>Mtumiaji</th>
                                <th>Kifaa</th>
                                <th>Chanzo (Referer)</th>
                                <th>Muda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentViews as $view)
                                <tr>
                                    <td>
                                        <code style="color: var(--adm-emerald); font-weight: 700; font-size: 13px;">{{ $view->path }}</code>
                                    </td>
                                    <td>
                                        @if($view->user)
                                            <span style="font-weight: 700; color: var(--adm-text-main);">{{ $view->user->name }}</span>
                                        @else
                                            <span style="color: var(--adm-text-sub); font-size: 12px;">Mgeni (Bila Kuingia)</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($view->device_type === 'mobile')
                                            <span class="adm-badge adm-badge-blue">📱 Simu</span>
                                        @elseif($view->device_type === 'tablet')
                                            <span class="adm-badge adm-badge-purple">📟 Tablet</span>
                                        @else
                                            <span class="adm-badge adm-badge-gray">💻 Kompyuta</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="color: var(--adm-text-sub); font-size: 12px;">{{ $view->referer ? parse_url($view->referer, PHP_URL_HOST) ?? $view->referer : 'Moja kwa moja' }}</span>
                                    </td>
                                    <td>
                                        <span style="color: var(--adm-text-sub); font-size: 12px;">{{ $view->created_at ? $view->created_at->diffForHumans() : '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: var(--adm-text-sub); padding: 24px;">Hakuna data ya wageni bado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 2: USIMAMIZI WA WATUMIAJI (USER MANAGEMENT) --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'users')
            <div class="adm-kpi-card" style="margin-bottom: 24px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <h2 style="font-size: 18px; font-weight: 900; margin: 0; color: var(--adm-text-main);">
                            👥 Orodha na Usimamizi wa Watumiaji (Total User Control)
                        </h2>
                        <p style="margin: 3px 0 0; font-size: 13px; color: var(--adm-text-sub);">
                            Tazama kila mtumiaji, badilisha mamlaka ya Admin au Supporter, na angalia shughuli zao.
                        </p>
                    </div>

                    {{-- Search & Filter Form --}}
                    <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px;">
                        <input type="hidden" name="tab" value="users">
                        
                        <div style="display: flex; align-items: center; gap: 6px; background: var(--adm-hover-bg); border: 1px solid var(--adm-card-border); border-radius: 10px; padding: 4px 12px;">
                            <span>🔍</span>
                            <input type="text" name="user_search" value="{{ $userSearch }}" placeholder="Tafuta jina au barua pepe..." style="background: transparent; border: none; outline: none; font-size: 13px; color: var(--adm-text-main); width: 190px;">
                        </div>

                        <select name="user_role" onchange="this.form.submit()" style="background: var(--adm-hover-bg); border: 1px solid var(--adm-card-border); border-radius: 10px; padding: 8px 12px; font-size: 13px; color: var(--adm-text-main); font-weight: 600; outline: none;">
                            <option value="all" {{ $userRole === 'all' ? 'selected' : '' }}>Hadhi Zote</option>
                            <option value="admin" {{ $userRole === 'admin' ? 'selected' : '' }}>Ma-Admin Pekee ({{ $totalAdmins }})</option>
                            <option value="supporter" {{ $userRole === 'supporter' ? 'selected' : '' }}>Waungaji Mkono / Supporters ({{ $totalSupporters }})</option>
                            <option value="regular" {{ $userRole === 'regular' ? 'selected' : '' }}>Watumiaji wa Kawaida</option>
                        </select>

                        <button type="submit" class="adm-action-btn adm-badge-emerald" style="padding: 8px 14px;">Chuja</button>
                        @if($userSearch || $userRole !== 'all')
                            <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="adm-action-btn adm-badge-gray" style="padding: 8px 12px; text-decoration: none;">Futa Chujio</a>
                        @endif
                    </form>
                </div>

                {{-- Users Table --}}
                <div style="overflow-x: auto;">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Mtumiaji (Jina & Barua Pepe)</th>
                                <th>Aina ya Akaunti</th>
                                <th>Hadhi (Role)</th>
                                <th>Michango & Bookmarks</th>
                                <th>Tarehe ya Kujiunga</th>
                                <th style="text-align: right;">Hatua za Msimamizi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, var(--adm-emerald), #047857); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; text-transform: uppercase; flex-shrink: 0;">
                                                {{ substr($u->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 800; color: var(--adm-text-main); font-size: 14px;">
                                                    {{ $u->name }}
                                                    @if($u->id === auth()->id())
                                                        <span class="adm-badge adm-badge-emerald" style="font-size: 10px; margin-left: 6px;">Wewe</span>
                                                    @endif
                                                </div>
                                                <div style="color: var(--adm-text-sub); font-size: 12.5px;">{{ $u->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $providers = $u->socialAccounts->pluck('provider')->toArray();
                                        @endphp
                                        @if(in_array('google', $providers))
                                            <span class="adm-badge adm-badge-blue">Google</span>
                                        @elseif(in_array('x', $providers))
                                            <span class="adm-badge adm-badge-gray">X (Twitter)</span>
                                        @else
                                            <span class="adm-badge adm-badge-gray">Barua Pepe / Password</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                            @if($u->isAdmin())
                                                <span class="adm-badge adm-badge-emerald">👑 ADMIN</span>
                                            @endif
                                            @if($u->isSupporter())
                                                <span class="adm-badge adm-badge-gold">⭐ SUPPORTER</span>
                                            @endif
                                            @if(! $u->isAdmin() && ! $u->isSupporter())
                                                <span class="adm-badge adm-badge-gray">Mwanachama wa Kawaida</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size: 12.5px; color: var(--adm-text-main);">
                                            <span>🔖 {{ $u->bookmarks_count }} bookmarks</span>
                                            <br>
                                            <span style="color: var(--adm-emerald); font-weight: 700;">💳 {{ $u->payments_count }} michango</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: var(--adm-text-sub); font-size: 12.5px;">
                                            {{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 8px;">
                                            {{-- Toggle Admin --}}
                                            @if($u->id !== auth()->id())
                                                <form action="{{ route('admin.users.toggle-admin', $u) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Je, una uhakika unataka kubadilisha hadhi ya Admin kwa mtumiaji huyu?');">
                                                    @csrf
                                                    @if($u->is_admin)
                                                        <button type="submit" class="adm-action-btn adm-badge-red" title="Ondoa Admin">
                                                            <span>Ondoa Admin</span>
                                                        </button>
                                                    @else
                                                        <button type="submit" class="adm-action-btn adm-badge-emerald" title="Mpe Admin">
                                                            <span>👑 Fanya Admin</span>
                                                        </button>
                                                    @endif
                                                </form>
                                            @endif

                                            {{-- Toggle Supporter --}}
                                            <form action="{{ route('admin.users.toggle-supporter', $u) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                @if($u->is_supporter)
                                                    <button type="submit" class="adm-action-btn adm-badge-gray" title="Ondoa Supporter">
                                                        <span>Zima Supporter</span>
                                                    </button>
                                                @else
                                                    <button type="submit" class="adm-action-btn adm-badge-gold" title="Washa Supporter">
                                                        <span>⭐ Washa Supporter</span>
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; color: var(--adm-text-sub); padding: 32px;">Hakuna mtumiaji aliyelingana na vigezo vya utafutaji.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--adm-card-border);">
                    {{ $users->links() }}
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 3: MALIPO & SADAKA (CLICKPESA & REVENUE) --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'payments')
            <div class="adm-kpi-card" style="margin-bottom: 24px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 22px;">
                    <div>
                        <h2 style="font-size: 18px; font-weight: 900; margin: 0; color: var(--adm-text-main);">
                            💳 Ufuatiliaji wa Sadaka na Michango (Transactions)
                        </h2>
                        <p style="margin: 3px 0 0; font-size: 13px; color: var(--adm-text-sub);">
                            Malipo yote yaliyopokelewa kupitia ClickPesa, Pesapal au Simulation.
                        </p>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <a href="{{ route('admin.payments.export') }}" class="adm-action-btn adm-badge-emerald" style="padding: 9px 16px; text-decoration: none;">
                            <span>📥 Pakua Ripoti Kamili (CSV / Excel)</span>
                        </a>
                    </div>
                </div>

                {{-- Payment Filters & Search --}}
                <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px; padding: 12px 16px; background: var(--adm-hover-bg); border-radius: 12px; border: 1px solid var(--adm-card-border);">
                    <input type="hidden" name="tab" value="payments">

                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span style="font-size: 12.5px; font-weight: 700; color: var(--adm-text-sub);">Hali:</span>
                        <a href="{{ route('admin.dashboard', ['tab' => 'payments', 'payment_status' => 'all', 'payment_search' => $paymentSearch]) }}" class="adm-badge {{ $paymentStatus === 'all' ? 'adm-badge-emerald' : 'adm-badge-gray' }}" style="text-decoration: none; padding: 5px 12px;">Zote</a>
                        <a href="{{ route('admin.dashboard', ['tab' => 'payments', 'payment_status' => 'completed', 'payment_search' => $paymentSearch]) }}" class="adm-badge {{ $paymentStatus === 'completed' ? 'adm-badge-emerald' : 'adm-badge-gray' }}" style="text-decoration: none; padding: 5px 12px;">Zilizokamilika ({{ $paymentsCompletedCount }})</a>
                        <a href="{{ route('admin.dashboard', ['tab' => 'payments', 'payment_status' => 'pending', 'payment_search' => $paymentSearch]) }}" class="adm-badge {{ $paymentStatus === 'pending' ? 'adm-badge-gold' : 'adm-badge-gray' }}" style="text-decoration: none; padding: 5px 12px;">Zinazosubiri ({{ $paymentsPendingCount }})</a>
                        <a href="{{ route('admin.dashboard', ['tab' => 'payments', 'payment_status' => 'failed', 'payment_search' => $paymentSearch]) }}" class="adm-badge {{ $paymentStatus === 'failed' ? 'adm-badge-red' : 'adm-badge-gray' }}" style="text-decoration: none; padding: 5px 12px;">Zilizoshindwa ({{ $paymentsFailedCount }})</a>
                    </div>

                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" name="payment_search" value="{{ $paymentSearch }}" placeholder="Kumbukumbu, jina, namba ya simu..." style="background: var(--adm-card-bg); border: 1px solid var(--adm-card-border); border-radius: 8px; padding: 6px 12px; font-size: 13px; color: var(--adm-text-main); outline: none; width: 220px;">
                        <button type="submit" class="adm-action-btn adm-badge-emerald" style="padding: 7px 12px;">Tafuta</button>
                    </div>
                </form>

                {{-- Payments Table --}}
                <div style="overflow-x: auto;">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Kumbukumbu</th>
                                <th>Mchangiaji</th>
                                <th>Kiasi</th>
                                <th>Lango & Njia</th>
                                <th>Hali</th>
                                <th>Tarehe</th>
                                <th style="text-align: right;">Risiti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $p)
                                <tr>
                                    <td>
                                        <code style="font-weight: 800; color: var(--adm-emerald); font-size: 13px;">{{ $p->reference }}</code>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: var(--adm-text-main);">
                                            {{ $p->is_anonymous ? 'Mchangiaji Mkarimu (Binafsi)' : ($p->donor_name ?: 'Bila Jina') }}
                                        </div>
                                        <div style="font-size: 12px; color: var(--adm-text-sub);">
                                            {{ $p->donor_phone ?: $p->donor_email ?: '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span style="font-weight: 900; font-size: 14.5px; color: var(--adm-text-main);">
                                            {{ number_format($p->amount) }}
                                        </span>
                                        <span style="font-size: 11px; font-weight: 700; color: var(--adm-text-sub);">{{ $p->currency }}</span>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            @if($p->gateway === 'clickpesa')
                                                <span class="adm-badge adm-badge-emerald">ClickPesa</span>
                                            @elseif($p->gateway === 'pesapal')
                                                <span class="adm-badge adm-badge-blue">Pesapal</span>
                                            @else
                                                <span class="adm-badge adm-badge-gray">Simulation</span>
                                            @endif
                                            <span style="font-size: 12px; color: var(--adm-text-sub);">{{ $p->payment_method ?: 'Mobile Money' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($p->status === 'completed')
                                            <span class="adm-badge adm-badge-emerald">✓ Imekamilika</span>
                                        @elseif($p->status === 'pending')
                                            <span class="adm-badge adm-badge-gold">⏳ Inasubiri</span>
                                        @else
                                            <span class="adm-badge adm-badge-red">✕ Imeshindwa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-size: 12.5px; color: var(--adm-text-sub);">
                                            {{ $p->created_at ? $p->created_at->format('d M Y, H:i') : '-' }}
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('payments.receipt', ['reference' => $p->reference]) }}" target="_blank" class="adm-action-btn adm-badge-blue" style="text-decoration: none; padding: 5px 11px; font-size: 12px;">
                                            <span>Fungua ↗</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; color: var(--adm-text-sub); padding: 32px;">Hakuna miamala inayolingana na vigezo vilivyochaguliwa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--adm-card-border);">
                    {{ $payments->links() }}
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 4: MAUDHUI (HADITH, DUAA, QURAN) --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'content')
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 24px;">
                <div class="adm-kpi-card">
                    <span style="font-size: 12px; font-weight: 800; color: var(--adm-text-sub); text-transform: uppercase;">Hadithi Zote</span>
                    <div style="font-size: 32px; font-weight: 900; color: var(--adm-text-main); margin: 6px 0;">
                        {{ number_format($totalHadith) }}
                    </div>
                    <span class="adm-badge adm-badge-emerald">{{ number_format($totalHadithPublished) }} Zimechapishwa</span>
                </div>

                <div class="adm-kpi-card">
                    <span style="font-size: 12px; font-weight: 800; color: var(--adm-text-sub); text-transform: uppercase;">Duaa Zote</span>
                    <div style="font-size: 32px; font-weight: 900; color: var(--adm-text-main); margin: 6px 0;">
                        {{ number_format($totalDuas) }}
                    </div>
                    <span class="adm-badge adm-badge-purple">{{ number_format($totalDuasPublished) }} Zimechapishwa</span>
                </div>

                <div class="adm-kpi-card">
                    <span style="font-size: 12px; font-weight: 800; color: var(--adm-text-sub); text-transform: uppercase;">Aya za Quran</span>
                    <div style="font-size: 32px; font-weight: 900; color: var(--adm-text-main); margin: 6px 0;">
                        {{ number_format($totalAyahs) }}
                    </div>
                    <span class="adm-badge adm-badge-blue">Sura 114 Kamili</span>
                </div>
            </div>

            <div class="adm-kpi-card">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px; margin-bottom: 18px;">
                    <div>
                        <h2 style="font-size: 17px; font-weight: 900; margin: 0; color: var(--adm-text-main);">
                            📖 Usimamizi wa Hadithi (Publish / Unpublish)
                        </h2>
                        <p style="margin: 2px 0 0; font-size: 12.5px; color: var(--adm-text-sub);">
                            Chagua hadithi unayotaka kuweka hadharani au kuificha.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 6px;">
                        <input type="hidden" name="tab" value="content">
                        <input type="text" name="hadith_search" value="{{ $hadithSearch }}" placeholder="Tafuta namba au maneno ya Kiswahili..." style="background: var(--adm-hover-bg); border: 1px solid var(--adm-card-border); border-radius: 8px; padding: 7px 12px; font-size: 13px; color: var(--adm-text-main); outline: none; width: 240px;">
                        <button type="submit" class="adm-action-btn adm-badge-emerald" style="padding: 8px 12px;">Tafuta</button>
                    </form>
                </div>

                <div style="overflow-x: auto;">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th># Namba</th>
                                <th>Mkusanyiko (Collection)</th>
                                <th>Matini ya Kiswahili</th>
                                <th>Hali</th>
                                <th style="text-align: right;">Kitendo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hadiths as $h)
                                <tr>
                                    <td>
                                        <code style="font-weight: 800; color: var(--adm-emerald);">#{{ $h->hadith_number }}</code>
                                    </td>
                                    <td>
                                        <span style="font-weight: 700; font-size: 12.5px;">{{ $h->chapter?->book?->collection?->name ?? 'Mkusanyiko' }}</span>
                                    </td>
                                    <td style="max-width: 440px;">
                                        <p style="margin: 0; font-size: 12.5px; color: var(--adm-text-sub); overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                            {{ $h->swahili_text }}
                                        </p>
                                    </td>
                                    <td>
                                        @if($h->is_published)
                                            <span class="adm-badge adm-badge-emerald">Hadharani</span>
                                        @else
                                            <span class="adm-badge adm-badge-gray">Imehifadhiwa (Draft)</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 8px;">
                                            <a href="{{ route('hadith.show', $h) }}" target="_blank" class="adm-action-btn adm-badge-blue" style="text-decoration: none; padding: 5px 10px;">
                                                <span>Ona ↗</span>
                                            </a>
                                            <form action="{{ route('admin.hadith.toggle-publish', $h) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="adm-action-btn {{ $h->is_published ? 'adm-badge-red' : 'adm-badge-emerald' }}" style="padding: 5px 11px;">
                                                    <span>{{ $h->is_published ? 'Ficha' : 'Chapisha' }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: var(--adm-text-sub); padding: 32px;">Hakuna hadithi zilizopatikana.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--adm-card-border);">
                    {{ $hadiths->links() }}
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 5: MFUMO & CLICKPESA (SYSTEM & TOOLS) --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'system')
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 20px;">
                
                {{-- ClickPesa Gateway Card --}}
                <div class="adm-kpi-card" style="border-top: 4px solid var(--adm-emerald);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h2 style="font-size: 16px; font-weight: 800; margin: 0; color: var(--adm-text-main);">
                            💳 Hali ya Lango la ClickPesa
                        </h2>
                        @if($systemInfo['clickpesa_configured'])
                            <span class="adm-badge adm-badge-emerald">✓ IMEUNGANISHWA LIVE</span>
                        @else
                            <span class="adm-badge adm-badge-gold">BADO KUKAMILIKA</span>
                        @endif
                    </div>

                    <p style="font-size: 13px; color: var(--adm-text-sub); margin-bottom: 16px;">
                        Lango la ClickPesa linawezesha upokeaji wa malipo kupitia <strong>M-Pesa, Tigo Pesa, Airtel Money, HaloPesa na Kadi (Visa/Mastercard)</strong> nchini Tanzania.
                    </p>

                    <div style="background: var(--adm-hover-bg); border-radius: 12px; padding: 14px; margin-bottom: 16px; font-size: 13px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: var(--adm-text-sub);">Lango Linalotumika:</span>
                            <strong style="color: var(--adm-emerald); text-transform: uppercase;">{{ $systemInfo['payment_gateway'] }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: var(--adm-text-sub);">Client ID:</span>
                            <strong>{{ config('services.clickpesa.client_id') ? substr(config('services.clickpesa.client_id'), 0, 10) . '...' : 'Haijawekwa' }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--adm-text-sub);">Webhook URL:</span>
                            <code style="color: var(--adm-blue); font-size: 12px;">{{ url('/malipo/webhook/clickpesa') }}</code>
                        </div>
                    </div>

                    <div style="font-size: 12px; color: var(--adm-text-sub);">
                        Kujaribu mawasiliano ya moja kwa moja kwenye seva, tumia:
                        <code style="display: block; margin-top: 6px; padding: 8px 12px; background: rgba(0,0,0,0.06); border-radius: 8px;">php artisan clickpesa:test</code>
                    </div>
                </div>

                {{-- System Tools & Cache --}}
                <div class="adm-kpi-card" style="border-top: 4px solid var(--adm-blue);">
                    <h2 style="font-size: 16px; font-weight: 800; margin: 0 0 16px; color: var(--adm-text-main);">
                        ⚡ Zana za Mfumo (System Operations)
                    </h2>

                    <p style="font-size: 13px; color: var(--adm-text-sub); margin-bottom: 18px;">
                        Tumia zana hizi kusafisha cache ya mfumo na kuboresha kasi ya tovuti bila kuingia kwenye terminal.
                    </p>

                    <form action="{{ route('admin.system.clear-cache') }}" method="POST" style="margin-bottom: 22px;">
                        @csrf
                        <button type="submit" class="adm-action-btn adm-badge-gold" style="width: 100%; justify-content: center; padding: 12px; font-size: 14px; border: 1px solid rgba(245, 158, 11, 0.4);">
                            <span>⚡ Safisha Cache Zote za Mfumo (Views, Routes & Config)</span>
                        </button>
                    </form>

                    <div style="border-top: 1px solid var(--adm-card-border); padding-top: 14px; font-size: 12.5px; color: var(--adm-text-sub);">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span>Toleo la PHP:</span>
                            <strong style="color: var(--adm-text-main);">{{ $systemInfo['php_version'] }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span>Toleo la Laravel:</span>
                            <strong style="color: var(--adm-text-main);">v{{ $systemInfo['laravel_version'] }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span>Database:</span>
                            <strong style="color: var(--adm-text-main); text-transform: uppercase;">{{ $systemInfo['db_driver'] }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Muda wa Seva:</span>
                            <strong style="color: var(--adm-text-main);">{{ $systemInfo['server_time'] }}</strong>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Kumbukumbu za vitendo vya Admin --}}
            <div class="adm-kpi-card" style="margin-top: 20px;">
                <h2 style="font-size: 16px; font-weight: 800; margin: 0 0 14px; color: var(--adm-text-main);">
                    📝 Shughuli za Admin za Karibuni
                </h2>
                <div style="overflow-x: auto;">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Tarehe</th>
                                <th>Admin</th>
                                <th>Kitendo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($adminLogs as $log)
                                <tr>
                                    <td style="white-space: nowrap;">{{ $log->created_at?->format('d M Y H:i') }}</td>
                                    <td>{{ $log->user?->email ?? '—' }}</td>
                                    <td>{{ $log->description }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: var(--adm-text-sub); padding: 24px;">Bado hakuna shughuli zilizorekodiwa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</x-layouts.app>
