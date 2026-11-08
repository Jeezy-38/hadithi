<x-layouts.app :noindex="true" :title="'Admin Panel — Jopo Kuu la Utawala'">
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


            .adm-title { font-size: 24px; font-weight: 800; margin: 0; color: var(--adm-text-main); letter-spacing: -0.3px; line-height: 1.2; }
            .adm-subtitle { margin: 4px 0 0; color: var(--adm-text-sub); font-size: 13.5px; }
            .adm-grid { display: grid; gap: 16px; margin-bottom: 16px; }
            .adm-grid-kpi { grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); }
            .adm-grid-2 { grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); }
            .adm-grid-4 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            @media (max-width: 980px) { .adm-grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
            @media (max-width: 620px) { .adm-grid-4 { grid-template-columns: 1fr; } .adm-grid-2 { grid-template-columns: 1fr; } }
            .adm-kpi { padding: 18px 20px; }
            .adm-kpi-label { font-size: 12px; font-weight: 600; color: var(--adm-text-sub); letter-spacing: .02em; margin-bottom: 8px; }
            .adm-kpi-value { font-size: 30px; font-weight: 800; color: var(--adm-text-main); line-height: 1.1; letter-spacing: -0.5px; }
            .adm-kpi-value small { font-size: 13px; font-weight: 700; color: var(--adm-text-sub); letter-spacing: 0; }
            .adm-kpi-sub { margin-top: 8px; font-size: 12.5px; color: var(--adm-text-sub); }
            .adm-footnote { margin: 2px 2px 22px; font-size: 12px; line-height: 1.6; color: var(--adm-text-sub); }
            .adm-card-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
            .adm-card-head h2 { margin: 0; font-size: 15px; font-weight: 700; color: var(--adm-text-main); }
            .adm-card-sub { margin: 3px 0 0; font-size: 12.5px; color: var(--adm-text-sub); }
            .adm-card-note { font-size: 11.5px; font-weight: 600; color: var(--adm-text-sub); white-space: nowrap; }
            .adm-chart { display: flex; align-items: flex-end; justify-content: space-between; gap: 10px; height: 180px; border-bottom: 1px solid var(--adm-card-border); padding-top: 8px; }
            .adm-chart-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; }
            .adm-chart-num { font-size: 11px; font-weight: 700; color: var(--adm-text-sub); }
            .adm-chart-bar { width: 100%; max-width: 34px; border-radius: 6px 6px 0 0; background: var(--adm-emerald); opacity: .85; }
            .adm-chart-lbl { font-size: 11px; color: var(--adm-text-sub); white-space: nowrap; margin-bottom: -22px; }
            .adm-chart { margin-bottom: 26px; }
            .adm-rows { display: flex; flex-direction: column; gap: 12px; }
            .adm-row-top { display: flex; align-items: baseline; justify-content: space-between; gap: 10px; margin-bottom: 5px; }
            .adm-row-name { font-size: 13px; font-weight: 600; color: var(--adm-text-main); word-break: break-word; }
            .adm-row-name code { margin-left: 6px; font-size: 11.5px; font-weight: 500; color: var(--adm-text-sub); }
            .adm-row-val { font-size: 13px; font-weight: 700; color: var(--adm-text-main); white-space: nowrap; }
            .adm-row-val small { font-size: 11.5px; font-weight: 600; color: var(--adm-text-sub); margin-left: 4px; }
            .adm-meter { height: 6px; border-radius: 999px; background: var(--adm-hover-bg); overflow: hidden; }
            .adm-meter i { display: block; height: 100%; border-radius: 999px; background: var(--adm-emerald); opacity: .85; }
            .adm-muted { color: var(--adm-text-sub); font-size: 12.5px; }
            .adm-empty { color: var(--adm-text-sub); font-size: 13px; margin: 0; }
            .adm-code { font-size: 12.5px; color: var(--adm-text-main); background: transparent; }

            .adm-tab-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 9px 16px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 700;
                text-decoration: none;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                color: var(--adm-text-sub);
                border: 1px solid transparent;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
                user-select: none;
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

            .adm-tabs-rail {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 24px;
                overflow-x: auto;
                padding: 4px 2px 10px;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }
            .adm-tabs-rail::-webkit-scrollbar {
                display: none;
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

            .adm-table-wrap {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                border-radius: 12px;
            }
            .adm-table {
                width: 100%;
                min-width: 580px;
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
            .admin-dashboard-root { overflow-x: hidden; width: 100%; box-sizing: border-box; }
            .admin-dashboard-root img { max-width: 100%; }
            @media (max-width: 768px) {
                .admin-dashboard-root { padding: 14px 10px 90px !important; }
                .adm-grid-kpi { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; gap: 10px !important; }
                .adm-grid-2, .adm-grid-4 { grid-template-columns: 1fr !important; }
                .adm-top-bar {
                    flex-direction: column !important;
                    align-items: stretch !important;
                    gap: 14px !important;
                }
                .adm-top-bar > div:last-child {
                    display: flex !important;
                    flex-wrap: wrap !important;
                    gap: 8px !important;
                    justify-content: flex-start !important;
                }
                .adm-tab-btn { padding: 8px 12px !important; font-size: 12px !important; }
                .adm-kpi-card { padding: 14px !important; border-radius: 14px !important; }
                .adm-table th, .adm-table td { padding: 10px 10px; font-size: 12.5px; }
                .admin-dashboard-root div[style*="grid-template-columns"] {
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 10px !important;
                }
            }
            @media (max-width: 480px) {
                .admin-dashboard-root div[style*="minmax(360px"],
                .admin-dashboard-root div[style*="minmax(260px"] { grid-template-columns: 1fr !important; }
                .admin-dashboard-root [style*="flex-wrap"] { gap: 8px !important; }
                .admin-dashboard-root h1.adm-title { font-size: 20px !important; }
                .admin-dashboard-root p.adm-subtitle { font-size: 12px !important; }
                /* Search boxes with a fixed 220px input overflowed on phones */
                .admin-dashboard-root form > div:has(> input[type="text"][style*="width: 220px"]) { width: 100%; }
                .admin-dashboard-root input[type="text"][style*="width: 220px"] { width: auto !important; flex: 1 1 0; min-width: 0; }
                .admin-dashboard-root form:has(> input[type="text"][style*="width: 240px"]) { width: 100%; }
                .admin-dashboard-root input[type="text"][style*="width: 240px"] { width: auto !important; flex: 1 1 0; min-width: 0; }
                .adm-action-btn { padding: 6px 10px; font-size: 12px; }
            }
        </style>

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div style="background: var(--adm-emerald-subtle); border: 1px solid var(--adm-ring); border-radius: 14px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; color: var(--adm-emerald); font-size: 14px; font-weight: 700;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 18px;"></span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: transparent; border: none; color: inherit; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 14px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; color: #ef4444; font-size: 14px; font-weight: 700;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 18px;"></span>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: transparent; border: none; color: inherit; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
        @endif

        {{-- Top Header Banner --}}
        <div class="adm-top-bar" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--adm-card-border);">
            <div>
                <h1 class="adm-title">Jopo la Usimamizi</h1>
                <p class="adm-subtitle">Takwimu za wageni, watumiaji, maudhui, malipo na wadhamini.</p>
            </div>

            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span class="adm-badge adm-badge-emerald" style="padding: 6px 14px; font-size: 12.5px;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--adm-emerald); box-shadow: 0 0 8px var(--adm-emerald);"></span>
                    Msimamizi: {{ auth()->user()->name }}
                </span>
                
                <form action="{{ route('admin.system.clear-cache') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="adm-action-btn adm-badge-gold" style="padding: 7px 14px; border: 1px solid rgba(245, 158, 11, 0.3);" title="Safisha cache za mfumo">
                        <span>Safisha Cache</span>
                    </button>
                </form>

                <a href="{{ route('library') }}" class="read-btn" style="text-decoration: none; padding: 7px 16px; font-size: 13px;">
                    <span>Tovuti Kuu</span>
                    <span aria-hidden="true">↗</span>
                </a>
            </div>
        </div>

        {{-- Modular Navigation Tabs (Pills) --}}
        <div class="adm-tabs-rail">
            <a href="{{ route('admin.dashboard', ['tab' => 'overview']) }}" class="adm-tab-btn {{ $activeTab === 'overview' ? 'active' : '' }}">
                <span>Takwimu za Wageni</span>
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="adm-tab-btn {{ $activeTab === 'users' ? 'active' : '' }}">
                <span>Watumiaji</span>
                <span class="adm-badge adm-badge-blue" style="font-size: 11px; padding: 2px 7px;">{{ number_format($totalUsers) }}</span>
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'payments']) }}" class="adm-tab-btn {{ $activeTab === 'payments' ? 'active' : '' }}">
                <span>Malipo</span>
                <span class="adm-badge adm-badge-emerald" style="font-size: 11px; padding: 2px 7px;">{{ number_format($paymentsCompletedCount) }}</span>
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'content']) }}" class="adm-tab-btn {{ $activeTab === 'content' ? 'active' : '' }}">
                <span>Maudhui</span>
                <span class="adm-badge adm-badge-purple" style="font-size: 11px; padding: 2px 7px;">{{ number_format($totalHadith) }}</span>
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'feedback']) }}" class="adm-tab-btn {{ $activeTab === 'feedback' ? 'active' : '' }}">
                <span>Maoni & Mawazo</span>
                @if($newFeedbackCount > 0)
                    <span class="adm-badge adm-badge-emerald" style="font-size: 11px; padding: 2px 7px;">{{ number_format($newFeedbackCount) }} Mpya</span>
                @else
                    <span class="adm-badge" style="font-size: 11px; padding: 2px 7px; opacity: 0.7;">{{ number_format($totalFeedbackCount) }}</span>
                @endif
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'sponsors']) }}" class="adm-tab-btn {{ $activeTab === 'sponsors' ? 'active' : '' }}">
                <span>Wadhamini</span>
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'system']) }}" class="adm-tab-btn {{ $activeTab === 'system' ? 'active' : '' }}">
                <span>Mfumo</span>
            </a>
        </div>

        {{-- ========================================================================= --}}
        {{-- TAB 1: OVERVIEW & TRAFFIC --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'overview')
            @php
                $countryNames = ['TZ' => 'Tanzania', 'KE' => 'Kenya', 'UG' => 'Uganda', 'RW' => 'Rwanda', 'BI' => 'Burundi', 'CD' => 'DR Congo', 'MZ' => 'Msumbiji', 'ZM' => 'Zambia', 'MW' => 'Malawi', 'ZA' => 'Afrika Kusini', 'SO' => 'Somalia', 'ET' => 'Ethiopia', 'NG' => 'Nigeria', 'EG' => 'Misri', 'SA' => 'Saudi Arabia', 'AE' => 'UAE', 'OM' => 'Oman', 'QA' => 'Qatar', 'KW' => 'Kuwait', 'US' => 'Marekani', 'GB' => 'Uingereza', 'CA' => 'Kanada', 'DE' => 'Ujerumani', 'FR' => 'Ufaransa', 'IN' => 'India', 'PK' => 'Pakistan', 'CN' => 'China', 'AU' => 'Australia'];
                $deviceNames = ['mobile' => 'Simu', 'tablet' => 'Tablet', 'desktop' => 'Kompyuta'];
                $pageLabel = function (string $p) {
                    if ($p === '/') return 'Ukurasa wa mwanzo';
                    if (preg_match('#^/hadith/\d+#', $p)) return 'Hadith (ukurasa mmoja)';
                    $map = ['/hadith-ya-leo' => 'Hadith ya Leo', '/mada' => 'Mada', '/duaa' => 'Dua & Adhkar', '/quran' => 'Qur\'ani', '/changia' => 'Changia', '/wadhamini' => 'Wadhamini', '/jifunze' => 'Jifunze', '/kuhusu' => 'Kuhusu', '/vipendwa' => 'Vipendwa', '/tasbih' => 'Tasbih', '/qibla' => 'Qibla', '/ramadhani' => 'Ramadhani', '/nyakati-za-swala' => 'Nyakati za Swala'];
                    return $map[$p] ?? null;
                };
                $breakdowns = [
                    ['title' => 'Nchi', 'rows' => $byCountry, 'label' => fn ($k) => $k === null ? 'Haijulikani' : ($countryNames[$k] ?? $k)],
                    ['title' => 'Vyanzo vya wageni', 'rows' => $bySource, 'label' => fn ($k) => $k],
                    ['title' => 'Kifaa', 'rows' => $byDevice, 'label' => fn ($k) => $deviceNames[$k] ?? ucfirst((string) $k)],
                    ['title' => 'Kivinjari', 'rows' => $byBrowser, 'label' => fn ($k) => $k ?? 'Haijulikani'],
                    ['title' => 'Mfumo wa uendeshaji', 'rows' => $byOs, 'label' => fn ($k) => $k ?? 'Haijulikani'],
                ];
                $hasCountry = collect($byCountry)->contains(fn ($r) => $r['key'] !== null);
            @endphp

            {{-- KPI --}}
            <div class="adm-grid adm-grid-kpi">
                <div class="adm-kpi-card adm-kpi">
                    <div class="adm-kpi-label">Wageni leo</div>
                    <div class="adm-kpi-value">{{ number_format($uniqueToday) }}</div>
                    <div class="adm-kpi-sub">{{ number_format($viewsToday) }} kurasa zilizofunguliwa</div>
                </div>
                <div class="adm-kpi-card adm-kpi">
                    <div class="adm-kpi-label">Siku 7 zilizopita</div>
                    <div class="adm-kpi-value">{{ number_format($unique7Days) }}</div>
                    <div class="adm-kpi-sub">{{ number_format($views7Days) }} kurasa zilizofunguliwa</div>
                </div>
                <div class="adm-kpi-card adm-kpi">
                    <div class="adm-kpi-label">Siku 30 zilizopita</div>
                    <div class="adm-kpi-value">{{ number_format($unique30Days) }}</div>
                    <div class="adm-kpi-sub">{{ number_format($views30Days) }} kurasa zilizofunguliwa</div>
                </div>
                <div class="adm-kpi-card adm-kpi">
                    <div class="adm-kpi-label">Watumiaji waliosajiliwa</div>
                    <div class="adm-kpi-value">{{ number_format($totalUsers) }}</div>
                    <div class="adm-kpi-sub">+{{ number_format($newUsers7Days) }} wapya wiki hii &bull; {{ $totalSupporters }} supporters</div>
                </div>
                <div class="adm-kpi-card adm-kpi">
                    <div class="adm-kpi-label">Mapato ya sadaka</div>
                    <div class="adm-kpi-value">{{ number_format($totalTzs) }} <small>TZS</small></div>
                    <div class="adm-kpi-sub">Mwezi huu: {{ number_format($monthTzs) }} TZS @if($totalUsd > 0) &bull; ${{ number_format($totalUsd, 2) }}@endif</div>
                </div>
            </div>
            <p class="adm-footnote">"Wageni" = idadi ya wageni tofauti kwa kila siku (kitambulisho cha mgeni kinabadilika kila siku kwa faragha, kwa hiyo mtu aliyerudi siku mbili anahesabiwa mara mbili). Roboti na crawlers hazihesabiwi.</p>

            {{-- Trend + Top pages --}}
            <div class="adm-grid adm-grid-2">
                <div class="adm-kpi-card">
                    <div class="adm-card-head"><h2>Mwelekeo wa kurasa zilizofunguliwa (siku 7)</h2></div>
                    @php $maxDaily = max(1, ...array_column($dailyStats, 'count')); @endphp
                    <div class="adm-chart">
                        @foreach($dailyStats as $stat)
                            @php $height = max(4, round(($stat['count'] / $maxDaily) * 130)); @endphp
                            <div class="adm-chart-col">
                                <span class="adm-chart-num">{{ $stat['count'] }}</span>
                                <div class="adm-chart-bar" style="height: {{ $height }}px;"></div>
                                <span class="adm-chart-lbl">{{ $stat['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="adm-kpi-card">
                    <div class="adm-card-head"><h2>Kurasa zinazoongoza (siku 30)</h2></div>
                    @php $maxPage = max(1, (int) ($topPages->first()->total ?? 1)); @endphp
                    <div class="adm-rows">
                        @forelse($topPages as $page)
                            <div class="adm-row">
                                <div class="adm-row-top">
                                    <span class="adm-row-name">{{ $pageLabel($page->path) ?? $page->path }}@if($pageLabel($page->path)) <code>{{ $page->path }}</code>@endif</span>
                                    <span class="adm-row-val">{{ number_format($page->total) }}</span>
                                </div>
                                <div class="adm-meter"><i style="width: {{ round($page->total / $maxPage * 100) }}%"></i></div>
                            </div>
                        @empty
                            <p class="adm-empty">Bado hakuna data ya kutosha.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Visitor breakdowns --}}
            <div class="adm-grid adm-grid-4">
                @foreach ($breakdowns as $bd)
                    <div class="adm-kpi-card">
                        <div class="adm-card-head"><h2>{{ $bd['title'] }}</h2><span class="adm-card-note">siku 30</span></div>
                        <div class="adm-rows">
                            @forelse($bd['rows'] as $r)
                                <div class="adm-row">
                                    <div class="adm-row-top">
                                        <span class="adm-row-name">{{ ($bd['label'])($r['key']) }}</span>
                                        <span class="adm-row-val">{{ number_format($r['total']) }} <small>{{ $r['pct'] }}%</small></span>
                                    </div>
                                    <div class="adm-meter"><i style="width: {{ max(2, $r['pct']) }}%"></i></div>
                                </div>
                            @empty
                                <p class="adm-empty">Bado hakuna data.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
                <div class="adm-kpi-card">
                    <div class="adm-card-head"><h2>Watumiaji wapya</h2><a class="adm-card-note" href="{{ route('admin.dashboard', ['tab' => 'users']) }}">Wote &rarr;</a></div>
                    <div class="adm-rows">
                        @forelse($recentUsers as $u)
                            <div class="adm-row-top" style="margin: 0;">
                                <span class="adm-row-name">{{ $u->name }}</span>
                                <span class="adm-muted">{{ $u->created_at?->diffForHumans() }}</span>
                            </div>
                        @empty
                            <p class="adm-empty">Bado hakuna watumiaji.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @if(! $hasCountry)
                <p class="adm-footnote">Taarifa za nchi zinaanza kukusanywa kuanzia sasa (zinatoka kwenye CDN ya seva). Ikiwa bado hazionekani baada ya siku moja, seva yako haitumi header ya nchi; tumia Google Analytics kwa mgawanyo wa nchi na mikoa.</p>
            @endif

            {{-- Recent visits --}}
            <div class="adm-kpi-card" style="margin-top: 20px;">
                <div class="adm-card-head">
                    <div>
                        <h2>Ziara za hivi karibuni</h2>
                        <p class="adm-card-sub">Ukurasa waliofungua, mahali, kifaa, chanzo na muda. Anwani za IP hazihifadhiwi.</p>
                    </div>
                    <span class="adm-card-note">20 za mwisho</span>
                </div>
                <div class="adm-table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Muda</th>
                                <th>Ukurasa</th>
                                <th>Mtumiaji</th>
                                <th>Nchi</th>
                                <th>Kifaa</th>
                                <th>Chanzo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentViews as $view)
                                @php
                                    $srcHost = $view->referer_host ?: ($view->referer ? parse_url($view->referer, PHP_URL_HOST) : null);
                                @endphp
                                <tr>
                                    <td title="{{ $view->created_at?->format('d/m/Y H:i:s') }}"><span class="adm-muted">{{ $view->created_at ? $view->created_at->diffForHumans() : '-' }}</span></td>
                                    <td><code class="adm-code">{{ \Illuminate\Support\Str::limit($view->path, 40) }}</code></td>
                                    <td>
                                        @if($view->user)
                                            <strong>{{ $view->user->name }}</strong>
                                        @else
                                            <span class="adm-muted">Mgeni</span>
                                        @endif
                                    </td>
                                    <td>{{ $view->country ? ($countryNames[$view->country] ?? $view->country) : '-' }}</td>
                                    <td>{{ $deviceNames[$view->device_type] ?? $view->device_type }}@if($view->browser || $view->os) <span class="adm-muted">&middot; {{ trim(($view->browser ?? '').($view->browser && $view->os ? ' / ' : '').($view->os ?? '')) }}</span>@endif</td>
                                    <td><span class="adm-muted">{{ $srcHost ? preg_replace('/^www\./', '', $srcHost) : 'Moja kwa moja' }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="adm-empty" style="text-align: center; padding: 24px;">Hakuna data ya wageni bado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <p class="adm-footnote">Takwimu hizi zinakusanywa na app yenyewe{{ $trackedSince ? ' tangu '.\Carbon\Carbon::parse($trackedSince)->format('d/m/Y') : '' }}. Kwa ripoti za kina zaidi (umri, mikoa, muda wa kukaa, njia za kufikia), tumia Google Analytics.</p>

        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 2: USIMAMIZI WA WATUMIAJI (USER MANAGEMENT) --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'users')
            <div class="adm-kpi-card" style="margin-bottom: 24px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <h2 style="font-size: 18px; font-weight: 900; margin: 0; color: var(--adm-text-main);">
                            Orodha na Usimamizi wa Watumiaji (Total User Control)
                        </h2>
                        <p style="margin: 3px 0 0; font-size: 13px; color: var(--adm-text-sub);">
                            Tazama kila mtumiaji, badilisha mamlaka ya Admin au Supporter, na angalia shughuli zao.
                        </p>
                    </div>

                    {{-- Search & Filter Form --}}
                    <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px;">
                        <input type="hidden" name="tab" value="users">
                        
                        <div style="display: flex; align-items: center; gap: 6px; background: var(--adm-hover-bg); border: 1px solid var(--adm-card-border); border-radius: 10px; padding: 4px 12px;">
                            <span></span>
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
                <div class="adm-table-wrap">
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
                                                <span class="adm-badge adm-badge-emerald">ADMIN</span>
                                            @endif
                                            @if($u->isSupporter())
                                                <span class="adm-badge adm-badge-gold">SUPPORTER</span>
                                            @endif
                                            @if(! $u->isAdmin() && ! $u->isSupporter())
                                                <span class="adm-badge adm-badge-gray">Mwanachama wa Kawaida</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size: 12.5px; color: var(--adm-text-main);">
                                            <span>{{ $u->bookmarks_count }} bookmarks</span>
                                            <br>
                                            <span style="color: var(--adm-emerald); font-weight: 700;">{{ $u->payments_count }} michango</span>
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
                                                            <span>Fanya Admin</span>
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
                                                        <span>Washa Supporter</span>
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
                            Ufuatiliaji wa Sadaka na Michango (Transactions)
                        </h2>
                        <p style="margin: 3px 0 0; font-size: 13px; color: var(--adm-text-sub);">
                            Malipo yote yaliyopokelewa kupitia ClickPesa, Pesapal au Simulation.
                        </p>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <a href="{{ route('admin.payments.export') }}" class="adm-action-btn adm-badge-emerald" style="padding: 9px 16px; text-decoration: none;">
                            <span>Pakua Ripoti Kamili (CSV / Excel)</span>
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
                <div class="adm-table-wrap">
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
                                            <span class="adm-badge adm-badge-emerald">Imekamilika</span>
                                        @elseif($p->status === 'pending')
                                            <span class="adm-badge adm-badge-gold">⏳ Inasubiri</span>
                                        @else
                                            <span class="adm-badge adm-badge-red">&times; Imeshindwa</span>
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
        {{-- TAB: WADHAMINI (Hadithi ya Leo) --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'sponsors')
            @php
                $inp = 'background: var(--adm-card-bg); border: 1px solid var(--adm-card-border); border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--adm-text-main); outline: none; width: 100%; box-sizing: border-box;';
                $today = now()->toDateString();
            @endphp
            <div class="adm-kpi-card" style="margin-bottom: 24px;">
                <h2 style="font-size: 18px; font-weight: 900; margin: 0 0 4px; color: var(--adm-text-main);">Ongeza Mdhamini wa Hadithi ya Leo</h2>
                <p style="margin: 0 0 16px; font-size: 13px; color: var(--adm-text-sub);">Atatokea kwenye kadi ya «Hadithi ya Leo» ukurasa wa mwanzo. Wakiwa wengi, wanazunguka kwa siku. Tarehe tupu = bila kikomo.</p>
                <form method="POST" action="{{ route('admin.sponsors.store') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
                    @csrf
                    <label style="font-size: 12px; font-weight: 700; color: var(--adm-text-sub);">Jina la mdhamini *<input type="text" name="name" value="{{ old('name') }}" required maxlength="80" style="{{ $inp }}"></label>
                    <label style="font-size: 12px; font-weight: 700; color: var(--adm-text-sub);">Ujumbe mfupi<input type="text" name="tagline" value="{{ old('tagline') }}" maxlength="120" placeholder="Mfano: Darasa la Qur'ani kila Jumamosi" style="{{ $inp }}"></label>
                    <label style="font-size: 12px; font-weight: 700; color: var(--adm-text-sub);">Kiungo (https://...) *<input type="url" name="url" value="{{ old('url') }}" required maxlength="500" placeholder="https://wa.me/255... au tovuti" style="{{ $inp }}"></label>
                    <label style="font-size: 12px; font-weight: 700; color: var(--adm-text-sub);">Kiungo cha logo (si lazima)<input type="url" name="logo_url" value="{{ old('logo_url') }}" maxlength="500" placeholder="https://.../logo.png" style="{{ $inp }}"></label>
                    <label style="font-size: 12px; font-weight: 700; color: var(--adm-text-sub);">Kifurushi / nafasi *
                        <select name="slot" required style="{{ $inp }}">
                            <option value="today" @selected(old('slot', 'today') === 'today')>Siku / Wiki: Hadithi ya Leo</option>
                            <option value="main" @selected(old('slot') === 'main')>Mdhamini Mkuu: Hadithi ya Leo + mwanzo + kurasa za hadith</option>
                        </select>
                    </label>
                    <label style="font-size: 12px; font-weight: 700; color: var(--adm-text-sub);">Anaanza tarehe<input type="date" name="starts_at" value="{{ old('starts_at') }}" style="{{ $inp }}"></label>
                    <label style="font-size: 12px; font-weight: 700; color: var(--adm-text-sub);">Anaishia tarehe<input type="date" name="ends_at" value="{{ old('ends_at') }}" style="{{ $inp }}"></label>
                    <div style="display: flex; align-items: flex-end;"><button type="submit" class="adm-action-btn adm-badge-emerald" style="padding: 10px 18px;">Ongeza Mdhamini</button></div>
                </form>
                @if($errors->any())
                    <div style="margin-top: 12px; font-size: 13px; color: #ef4444;">@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>
                @endif
            </div>

            <div class="adm-kpi-card" style="margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 800; margin: 0 0 14px; color: var(--adm-text-main);">Orodha ya Wadhamini ({{ $sponsors->count() }})</h2>
                <div class="adm-table-wrap">
                    <table class="adm-table">
                        <thead><tr><th>Mdhamini</th><th>Nafasi</th><th>Kipindi</th><th>Hali</th><th>Waliobofya</th><th>Vitendo</th></tr></thead>
                        <tbody>
                            @forelse($sponsors as $sp)
                                @php
                                    $live = $sp->is_active && (! $sp->starts_at || $sp->starts_at->toDateString() <= $today) && (! $sp->ends_at || $sp->ends_at->toDateString() >= $today);
                                    $expired = $sp->ends_at && $sp->ends_at->toDateString() < $today;
                                @endphp
                                <tr>
                                    <td><b>{{ $sp->name }}</b>@if($sp->tagline)<div style="font-size: 12px; color: var(--adm-text-sub);">{{ $sp->tagline }}</div>@endif<a href="{{ $sp->url }}" target="_blank" rel="noopener" style="font-size: 12px;">{{ \Illuminate\Support\Str::limit($sp->url, 38) }}</a></td>
                                    <td>{{ $sp->slot === 'main' ? 'Mkuu' : 'Hadithi ya Leo' }}</td>
                                    <td>{{ $sp->starts_at?->format('d/m/Y') ?? 'Mara moja' }} → {{ $sp->ends_at?->format('d/m/Y') ?? 'Bila kikomo' }}</td>
                                    <td>
                                        @if($live)<span class="adm-badge adm-badge-emerald">Anaonekana sasa</span>
                                        @elseif($expired)<span class="adm-badge adm-badge-gray">Muda umeisha</span>
                                        @elseif(! $sp->is_active)<span class="adm-badge adm-badge-gray">Amezimwa</span>
                                        @else<span class="adm-badge adm-badge-gold">Anasubiri kuanza</span>@endif
                                    </td>
                                    <td><b>{{ number_format($sp->clicks) }}</b></td>
                                    <td style="white-space: nowrap;">
                                        <form method="POST" action="{{ route('admin.sponsors.toggle', $sp) }}" style="display: inline;">@csrf<button type="submit" class="adm-action-btn adm-badge-gold">{{ $sp->is_active ? 'Zima' : 'Washa' }}</button></form>
                                        <form method="POST" action="{{ route('admin.sponsors.delete', $sp) }}" style="display: inline;" onsubmit="return confirm('Futa mdhamini huyu kabisa?');">@csrf @method('DELETE')<button type="submit" class="adm-action-btn adm-badge-red">Futa</button></form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" style="text-align: center; padding: 24px; color: var(--adm-text-sub);">Hakuna wadhamini bado. Ongeza wa kwanza juu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
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
                            Usimamizi wa Hadithi (Publish / Unpublish)
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

                <div class="adm-table-wrap">
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
        {{-- TAB: MAONI NA MAWAZO YA WATUMIAJI (USER FEEDBACK) --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'feedback')
            {{-- KPI Summary Cards --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 24px;">
                <div class="adm-kpi-card">
                    <span style="font-size: 11.5px; font-weight: 800; color: var(--adm-text-sub); text-transform: uppercase;">Jumla ya Maoni</span>
                    <div style="font-size: 28px; font-weight: 900; color: var(--adm-text-main); margin: 6px 0;">
                        {{ number_format($feedbackCounts['all']) }}
                    </div>
                    <span class="adm-badge adm-badge-blue">{{ number_format($feedbackCounts['new']) }} Mapya Hayajasomwa</span>
                </div>
                <div class="adm-kpi-card">
                    <span style="font-size: 11.5px; font-weight: 800; color: var(--adm-text-sub); text-transform: uppercase;">💡 Mawazo Mapya</span>
                    <div style="font-size: 28px; font-weight: 900; color: var(--adm-text-main); margin: 6px 0;">
                        {{ number_format($feedbackCounts['feature']) }}
                    </div>
                    <span class="adm-badge adm-badge-emerald">Mapendekezo ya Features</span>
                </div>
                <div class="adm-kpi-card">
                    <span style="font-size: 11.5px; font-weight: 800; color: var(--adm-text-sub); text-transform: uppercase;">✍️ Marekebisho</span>
                    <div style="font-size: 28px; font-weight: 900; color: var(--adm-text-main); margin: 6px 0;">
                        {{ number_format($feedbackCounts['correction']) }}
                    </div>
                    <span class="adm-badge adm-badge-purple">Tafsiri na Tahajia</span>
                </div>
                <div class="adm-kpi-card">
                    <span style="font-size: 11.5px; font-weight: 800; color: var(--adm-text-sub); text-transform: uppercase;">🐛 Hitilafu / Bugs</span>
                    <div style="font-size: 28px; font-weight: 900; color: var(--adm-text-main); margin: 6px 0;">
                        {{ number_format($feedbackCounts['bug']) }}
                    </div>
                    <span class="adm-badge adm-badge-red">Ripoti za Matatizo</span>
                </div>
            </div>

            {{-- Filter & Content Table --}}
            <div class="adm-kpi-card">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px; margin-bottom: 20px;">
                    <div>
                        <h2 style="font-size: 18px; font-weight: 900; margin: 0; color: var(--adm-text-main);">
                            Mawazo na Maoni ya Watumiaji
                        </h2>
                        <p style="margin: 3px 0 0; font-size: 12.5px; color: var(--adm-text-sub);">
                            Ujumbe unaotumwa na watumiaji kupitia sanduku la maoni kwenye tovuti.
                        </p>
                    </div>

                    {{-- Filters --}}
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        <a href="{{ route('admin.dashboard', ['tab' => 'feedback']) }}" class="adm-tab-btn {{ !request('status') && !request('category') ? 'active' : '' }}" style="font-size: 12px; padding: 6px 12px;">Yote</a>
                        <a href="{{ route('admin.dashboard', ['tab' => 'feedback', 'status' => 'new']) }}" class="adm-tab-btn {{ request('status') === 'new' ? 'active' : '' }}" style="font-size: 12px; padding: 6px 12px;">Mapya Tu</a>
                        <a href="{{ route('admin.dashboard', ['tab' => 'feedback', 'category' => 'feature']) }}" class="adm-tab-btn {{ request('category') === 'feature' ? 'active' : '' }}" style="font-size: 12px; padding: 6px 12px;">💡 Mawazo</a>
                        <a href="{{ route('admin.dashboard', ['tab' => 'feedback', 'category' => 'correction']) }}" class="adm-tab-btn {{ request('category') === 'correction' ? 'active' : '' }}" style="font-size: 12px; padding: 6px 12px;">✍️ Marekebisho</a>
                        <a href="{{ route('admin.dashboard', ['tab' => 'feedback', 'category' => 'bug']) }}" class="adm-tab-btn {{ request('category') === 'bug' ? 'active' : '' }}" style="font-size: 12px; padding: 6px 12px;">🐛 Hitilafu</a>
                    </div>
                </div>

                <div class="adm-table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th style="width: 140px;">Tarehe & Aina</th>
                                <th>Mtumiaji & Mawasiliano</th>
                                <th style="min-width: 280px;">Ujumbe / Wazo</th>
                                <th>Ukurasa / Rejea</th>
                                <th>Hali (Status)</th>
                                <th style="text-align: right; width: 140px;">Vitendo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feedbackList as $fb)
                                <tr>
                                    <td>
                                        <div style="font-size: 12px; font-weight: 700; color: var(--adm-text-main); margin-bottom: 4px;">
                                            {{ $fb->created_at?->format('d M Y, H:i') }}
                                        </div>
                                        <span class="adm-badge {{ $fb->categoryBadgeColor() }}" style="font-size: 10.5px;">
                                            {{ $fb->categoryLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: var(--adm-text-main); font-size: 13px;">
                                            {{ $fb->name ?: ($fb->user?->name ?: 'Mgeni (Guest)') }}
                                        </div>
                                        @if($fb->contact)
                                            <div style="font-size: 12px; color: var(--adm-gold); margin-top: 2px;">
                                                {{ $fb->contact }}
                                            </div>
                                        @elseif($fb->user?->email)
                                            <div style="font-size: 12px; color: var(--adm-text-sub); margin-top: 2px;">
                                                {{ $fb->user->email }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-size: 13.5px; line-height: 1.5; color: var(--adm-text-main); white-space: pre-wrap; background: var(--adm-hover-bg); padding: 10px 14px; border-radius: 10px; border: 1px solid var(--adm-card-border);">{{ $fb->message }}</div>
                                    </td>
                                    <td style="font-size: 12px; color: var(--adm-text-sub);">
                                        @if($fb->hadith_ref)
                                            <div style="font-weight: 700; color: var(--adm-text-main); margin-bottom: 2px;">{{ $fb->hadith_ref }}</div>
                                        @endif
                                        @if($fb->page_url)
                                            <a href="{{ $fb->page_url }}" target="_blank" rel="noopener" style="color: var(--adm-blue); text-decoration: underline; word-break: break-all; font-size: 11.5px;">
                                                Angalia ukurasa ↗
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @if($fb->status === 'new')
                                            <span class="adm-badge adm-badge-blue" style="font-weight: 800;">Mpya</span>
                                        @elseif($fb->status === 'resolved')
                                            <span class="adm-badge adm-badge-emerald">Imetatuliwa</span>
                                        @else
                                            <span class="adm-badge" style="opacity: 0.8;">Imesomwa</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                            @if($fb->status !== 'resolved')
                                                <form method="POST" action="{{ route('admin.feedback.status', $fb) }}" style="display: inline;">
                                                    @csrf
                                                    <input type="hidden" name="status" value="resolved">
                                                    <button type="submit" class="adm-tab-btn" style="font-size: 11px; padding: 4px 8px; background: rgba(16, 185, 129, 0.15); color: #10b981; border-color: rgba(16, 185, 129, 0.3);" title="Weka kuwa imetatuliwa">✓ Tatua</button>
                                                </form>
                                            @endif
                                            @if($fb->status === 'new')
                                                <form method="POST" action="{{ route('admin.feedback.status', $fb) }}" style="display: inline;">
                                                    @csrf
                                                    <input type="hidden" name="status" value="read">
                                                    <button type="submit" class="adm-tab-btn" style="font-size: 11px; padding: 4px 8px;" title="Weka kuwa imesomwa">Soma</button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('admin.feedback.delete', $fb) }}" style="display: inline;" onsubmit="return confirm('Je, una uhakika unataka kufuta maoni haya?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="adm-tab-btn" style="font-size: 11px; padding: 4px 8px; color: #ef4444; border-color: rgba(239, 68, 68, 0.3);" title="Futa">✕</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; color: var(--adm-text-sub); padding: 36px;">
                                        Bado hakuna maoni au mapendekezo yaliyowasilishwa na watumiaji kwa kigezo hiki.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($feedbackList->hasPages())
                    <div style="margin-top: 18px;">
                        {{ $feedbackList->links() }}
                    </div>
                @endif
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
                            Hali ya Lango la ClickPesa
                        </h2>
                        @if($systemInfo['clickpesa_configured'])
                            <span class="adm-badge adm-badge-emerald">IMEUNGANISHWA LIVE</span>
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
                        Zana za Mfumo (System Operations)
                    </h2>

                    <p style="font-size: 13px; color: var(--adm-text-sub); margin-bottom: 18px;">
                        Tumia zana hizi kusafisha cache ya mfumo na kuboresha kasi ya tovuti bila kuingia kwenye terminal.
                    </p>

                    <form action="{{ route('admin.system.clear-cache') }}" method="POST" style="margin-bottom: 22px;">
                        @csrf
                        <button type="submit" class="adm-action-btn adm-badge-gold" style="width: 100%; justify-content: center; padding: 12px; font-size: 14px; border: 1px solid rgba(245, 158, 11, 0.4);">
                            <span>Safisha Cache Zote za Mfumo (Views, Routes & Config)</span>
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
                    Shughuli za Admin za Karibuni
                </h2>
                <div class="adm-table-wrap">
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
