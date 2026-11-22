<x-layouts.app :noindex="true" title="Dashibodi ya Msomaji · Bayt Ul Hadith">
    <div class="user-dash-root">
        <style>
            .user-dash-root {
                max-width: 1400px;
                margin: 0 auto;
                padding: 16px 16px 80px;
                font-family: inherit;
            }

            /* Container Layout */
            .dash-layout {
                display: flex;
                gap: 24px;
                align-items: flex-start;
            }

            /* Left Sidebar Navigation */
            .dash-sidebar {
                width: 260px;
                flex-shrink: 0;
                background: var(--bg-surface);
                border: 1px solid var(--border-color);
                border-radius: 18px;
                padding: 18px 14px;
                box-shadow: var(--card-shadow);
                position: sticky;
                top: 86px;
            }

            .dash-user-badge {
                display: flex;
                align-items: center;
                gap: 12px;
                padding-bottom: 16px;
                margin-bottom: 16px;
                border-bottom: 1px solid var(--border-subtle);
            }
            .dash-avatar {
                width: 44px;
                height: 44px;
                border-radius: 14px;
                background: linear-gradient(135deg, var(--gold-light) 0%, var(--gold) 60%, var(--gold-dark) 100%);
                color: var(--btn-gold-text);
                font-weight: 800;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 17px;
                box-shadow: 0 4px 12px rgba(56, 189, 248, 0.2);
                object-fit: cover;
                flex-shrink: 0;
            }
            .dash-user-name {
                font-size: 14px;
                font-weight: 800;
                color: var(--text-primary);
                line-height: 1.2;
                margin-bottom: 2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .dash-user-status {
                font-size: 11px;
                color: var(--gold);
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .dash-nav-group {
                margin-bottom: 18px;
            }
            .dash-nav-header {
                font-size: 10.5px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.8px;
                color: var(--gold);
                padding: 0 10px;
                margin-bottom: 6px;
            }
            .dash-nav-link {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 8.5px 12px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 600;
                color: var(--text-secondary);
                text-decoration: none;
                transition: all 0.15s ease;
                margin-bottom: 2px;
            }
            .dash-nav-link:hover {
                background: var(--bg-surface-hover);
                color: var(--text-primary);
                transform: translateX(2px);
            }
            .dash-nav-link.active {
                background: var(--gold-gradient);
                color: var(--btn-gold-text);
                font-weight: 800;
                box-shadow: 0 4px 14px rgba(56, 189, 248, 0.25);
            }
            .dash-nav-link.active .dash-nav-badge {
                background: rgba(7, 41, 63, 0.2);
                color: var(--btn-gold-text);
            }
            .dash-nav-link-left {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .dash-nav-badge {
                font-size: 11px;
                font-weight: 700;
                padding: 2px 7px;
                border-radius: 10px;
                background: var(--bg-surface-elevated);
                color: var(--gold);
                border: 1px solid var(--border-subtle);
            }
            .dash-nav-link:hover .dash-nav-badge {
                background: var(--gold-subtle);
            }

            .dash-back-link {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 10px 12px;
                margin-top: 10px;
                border-top: 1px solid var(--border-subtle);
                font-size: 12px;
                font-weight: 700;
                color: var(--gold);
                text-decoration: none;
                transition: all 0.15s ease;
            }
            .dash-back-link:hover {
                color: var(--gold-light);
                transform: translateX(-2px);
            }

            /* Main Content Column */
            .dash-main {
                flex: 1;
                min-width: 0;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            /* Top Search & Filter Bar */
            .dash-search-box {
                background: var(--bg-surface);
                border: 1px solid var(--border-color);
                border-radius: 16px;
                padding: 8px 14px;
                display: flex;
                align-items: center;
                gap: 12px;
                box-shadow: var(--card-shadow);
            }
            .dash-search-input {
                flex: 1;
                border: none;
                background: transparent;
                color: var(--text-primary);
                font-size: 13.5px;
                outline: none;
            }
            .dash-search-input::placeholder {
                color: var(--text-dim);
                opacity: 0.7;
            }
            .dash-search-btn {
                padding: 6px 14px;
                border-radius: 10px;
                background: var(--gold-gradient);
                color: var(--btn-gold-text);
                border: none;
                font-size: 12px;
                font-weight: 700;
                cursor: pointer;
                transition: transform 0.15s ease, filter 0.15s ease;
            }
            .dash-search-btn:hover {
                filter: brightness(1.1);
                transform: translateY(-1px);
            }

            /* Welcome Hero Card */
            .dash-hero-card {
                background: linear-gradient(135deg, var(--bg-surface) 0%, var(--bg-surface-elevated) 50%, var(--bg-surface) 100%);
                border: 1px solid var(--border-color);
                border-radius: 20px;
                padding: 26px 28px;
                box-shadow: var(--card-shadow);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                position: relative;
                overflow: hidden;
            }
            .dash-hero-card::after {
                content: '';
                position: absolute;
                right: -40px;
                bottom: -40px;
                width: 180px;
                height: 180px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(56, 189, 248, 0.12) 0%, transparent 70%);
                pointer-events: none;
            }
            .dash-hero-title {
                font-size: 24px;
                font-weight: 800;
                color: var(--text-primary);
                margin: 0 0 6px;
                letter-spacing: -0.3px;
            }
            .dash-hero-sub {
                font-size: 13.5px;
                color: var(--text-secondary);
                margin: 0;
                line-height: 1.5;
                max-width: 620px;
            }
            .dash-hero-actions {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-shrink: 0;
            }
            .dash-btn-primary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 18px;
                border-radius: 12px;
                background: var(--gold-gradient);
                color: var(--btn-gold-text);
                font-size: 13px;
                font-weight: 800;
                text-decoration: none;
                box-shadow: 0 4px 16px rgba(56, 189, 248, 0.25);
                transition: all 0.2s ease;
            }
            .dash-btn-primary:hover {
                filter: brightness(1.1);
                transform: translateY(-1.5px);
            }
            .dash-btn-secondary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 16px;
                border-radius: 12px;
                background: var(--bg-surface-elevated);
                color: var(--text-primary);
                border: 1px solid var(--border-color);
                font-size: 13px;
                font-weight: 700;
                text-decoration: none;
                transition: all 0.2s ease;
            }
            .dash-btn-secondary:hover {
                background: var(--bg-surface-hover);
                border-color: var(--gold);
                transform: translateY(-1.5px);
            }

            /* 4 KPI Metric Cards */
            .dash-stats-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 16px;
            }
            .dash-stat-card {
                background: var(--bg-surface);
                border: 1px solid var(--border-color);
                border-radius: 16px;
                padding: 18px 20px;
                box-shadow: var(--card-shadow);
                position: relative;
                overflow: hidden;
                transition: all 0.2s ease;
            }
            .dash-stat-card:hover {
                border-color: var(--gold);
                transform: translateY(-2px);
                box-shadow: var(--card-hover-shadow);
            }
            .dash-stat-label {
                font-size: 12px;
                color: var(--text-secondary);
                font-weight: 600;
                margin-bottom: 6px;
            }
            .dash-stat-val-row {
                display: flex;
                align-items: baseline;
                gap: 8px;
            }
            .dash-stat-val {
                font-size: 28px;
                font-weight: 800;
                color: var(--text-primary);
                line-height: 1;
            }
            .dash-stat-badge {
                font-size: 11px;
                font-weight: 700;
                color: var(--gold);
            }
            .dash-stat-bar {
                width: 100%;
                height: 5px;
                background: var(--bg-surface-elevated);
                border-radius: 9999px;
                margin-top: 12px;
                overflow: hidden;
            }
            .dash-stat-bar-fill {
                height: 100%;
                border-radius: 9999px;
                background: var(--gold-gradient);
            }

            /* Two-column middle section */
            .dash-columns-grid {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 20px;
            }
            .dash-card {
                background: var(--bg-surface);
                border: 1px solid var(--border-color);
                border-radius: 18px;
                padding: 22px;
                box-shadow: var(--card-shadow);
            }
            .dash-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-bottom: 14px;
                margin-bottom: 14px;
                border-bottom: 1px solid var(--border-subtle);
            }
            .dash-card-title {
                font-size: 16px;
                font-weight: 800;
                color: var(--text-primary);
                margin: 0;
            }
            .dash-card-desc {
                font-size: 12px;
                color: var(--text-muted);
                margin: 2px 0 0;
            }
            .dash-card-link {
                font-size: 12px;
                font-weight: 700;
                color: var(--gold);
                text-decoration: none;
            }
            .dash-card-link:hover {
                text-decoration: underline;
            }

            /* Recent Reads Items */
            .dash-item-list {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .dash-read-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                padding: 12px 14px;
                border-radius: 14px;
                background: var(--bg-surface-elevated);
                border: 1px solid var(--border-subtle);
                transition: all 0.2s ease;
            }
            .dash-read-item:hover {
                border-color: var(--gold);
                background: var(--bg-surface-hover);
                transform: translateX(2px);
            }
            .dash-item-left {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
            }
            .dash-item-avatar {
                width: 38px;
                height: 38px;
                border-radius: 12px;
                background: var(--gold-gradient);
                color: var(--btn-gold-text);
                font-weight: 800;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                flex-shrink: 0;
            }
            .dash-item-info {
                min-width: 0;
            }
            .dash-item-meta {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 11px;
                color: var(--gold);
                font-weight: 700;
                margin-bottom: 2px;
            }
            .dash-item-title {
                font-size: 13.5px;
                font-weight: 700;
                color: var(--text-primary);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                margin: 0;
            }
            .dash-item-btn {
                padding: 7px 14px;
                border-radius: 10px;
                background: var(--gold-gradient);
                color: var(--btn-gold-text);
                font-size: 12px;
                font-weight: 800;
                text-decoration: none;
                flex-shrink: 0;
                transition: transform 0.15s ease;
            }
            .dash-item-btn:hover {
                filter: brightness(1.1);
                transform: scale(1.03);
            }

            /* Monthly Goal Ring & Streak */
            .dash-goal-ring-wrap {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 14px 0 18px;
            }
            .dash-goal-box {
                position: relative;
                width: 130px;
                height: 130px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .dash-goal-svg {
                width: 100%;
                height: 100%;
                transform: rotate(-90deg);
            }
            .dash-goal-bg {
                fill: transparent;
                stroke: var(--bg-surface-elevated);
                stroke-width: 10;
            }
            .dash-goal-fill {
                fill: transparent;
                stroke: var(--gold);
                stroke-width: 10;
                stroke-linecap: round;
                transition: stroke-dashoffset 0.8s ease;
            }
            .dash-goal-content {
                position: absolute;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .dash-goal-num {
                font-size: 22px;
                font-weight: 900;
                color: var(--text-primary);
                line-height: 1;
            }
            .dash-goal-lbl {
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                color: var(--gold);
                letter-spacing: 0.5px;
                margin-top: 3px;
            }

            .dash-streak-container {
                background: var(--bg-surface-elevated);
                border: 1px solid var(--border-subtle);
                border-radius: 14px;
                padding: 14px;
            }
            .dash-streak-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 12px;
                font-weight: 700;
                margin-bottom: 10px;
            }
            .dash-days-row {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 5px;
                text-align: center;
            }
            .dash-day-pill {
                padding: 6px 0;
                border-radius: 8px;
                font-size: 11px;
                font-weight: 700;
                background: var(--bg-surface);
                color: var(--text-muted);
                border: 1px solid var(--border-subtle);
            }
            .dash-day-pill.active {
                background: var(--gold-gradient);
                color: var(--btn-gold-text);
                font-weight: 800;
                border-color: transparent;
                box-shadow: 0 2px 8px rgba(56, 189, 248, 0.3);
            }
            .dash-day-pill.today {
                border-color: var(--gold);
            }

            /* Recommendations Grid */
            .dash-recom-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
            }
            .dash-recom-card {
                padding: 14px 16px;
                border-radius: 14px;
                background: var(--bg-surface-elevated);
                border: 1px solid var(--border-subtle);
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 12px;
                transition: all 0.2s ease;
            }
            .dash-recom-card:hover {
                border-color: var(--gold);
                background: var(--bg-surface-hover);
            }
            .dash-recom-meta {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 11px;
                font-weight: 700;
                color: var(--gold);
                margin-bottom: 3px;
            }
            .dash-recom-title {
                font-size: 13.5px;
                font-weight: 700;
                color: var(--text-primary);
                line-height: 1.4;
                margin: 0 0 4px;
            }
            .dash-recom-excerpt {
                font-size: 12px;
                color: var(--text-secondary);
                line-height: 1.5;
                margin: 0;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .dash-recom-btn {
                padding: 6px 12px;
                border-radius: 8px;
                background: var(--bg-surface);
                border: 1px solid var(--border-color);
                color: var(--text-primary);
                font-size: 11.5px;
                font-weight: 700;
                text-decoration: none;
                flex-shrink: 0;
                transition: all 0.15s ease;
            }
            .dash-recom-btn:hover {
                background: var(--gold-gradient);
                color: var(--btn-gold-text);
                border-color: transparent;
            }

            /* Responsive Adjustments */
            @media (max-width: 1024px) {
                .dash-sidebar {
                    display: none;
                }
                .dash-stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
                .dash-columns-grid {
                    grid-template-columns: 1fr;
                }
                .dash-recom-grid {
                    grid-template-columns: 1fr;
                }
            }
            @media (max-width: 640px) {
                .dash-hero-card {
                    flex-direction: column;
                    align-items: flex-start;
                    padding: 20px 18px;
                }
                .dash-hero-actions {
                    width: 100%;
                    flex-direction: column;
                }
                .dash-btn-primary, .dash-btn-secondary {
                    width: 100%;
                    justify-content: center;
                }
                .dash-stats-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="dash-layout">

            <!-- SIDEBAR COLUMN -->
            <aside class="dash-sidebar">
                <!-- User Profile Head in Sidebar -->
                <div class="dash-user-badge">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="" class="dash-avatar" referrerpolicy="no-referrer">
                    @else
                        <div class="dash-avatar">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</div>
                    @endif
                    <div style="min-width: 0;">
                        <div class="dash-user-name" title="{{ $user->name }}">{{ $user->name }}</div>
                        <div class="dash-user-status">
                            @if($user->isAdmin())
                                <span>★ Msimamizi (Admin)</span>
                            @elseif($user->isSupporter())
                                <span>★ {{ $user->supporterBadge() }}</span>
                            @else
                                <span>Msomaji Halisi</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Navigation Groups -->
                <div class="dash-nav-group">
                    <div class="dash-nav-header">Nyumbani</div>
                    <a href="{{ route('profile') }}" class="dash-nav-link active">
                        <div class="dash-nav-link-left">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Muhtasari</span>
                        </div>
                    </a>
                </div>

                <div class="dash-nav-group">
                    <div class="dash-nav-header">Maktaba Yangu</div>
                    <a href="{{ route('bookmarks') }}" class="dash-nav-link">
                        <div class="dash-nav-link-left">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            <span>Vipendwa</span>
                        </div>
                        <span class="dash-nav-badge">{{ $stats['bookmarks'] }}</span>
                    </a>
                    <a href="{{ route('learning.index') }}" class="dash-nav-link">
                        <div class="dash-nav-link-left">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span>Nilizosoma</span>
                        </div>
                        <span class="dash-nav-badge">{{ $stats['learned'] }}</span>
                    </a>
                    <a href="{{ route('learning.memorize') }}" class="dash-nav-link">
                        <div class="dash-nav-link-left">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Kadi za Kuhifadhi</span>
                        </div>
                        <span class="dash-nav-badge">{{ $stats['cards'] }}</span>
                    </a>
                    <a href="{{ route('duaa.index') }}" class="dash-nav-link">
                        <div class="dash-nav-link-left">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span>Dua &amp; Adhkar</span>
                        </div>
                    </a>
                </div>

                <div class="dash-nav-group">
                    <div class="dash-nav-header">Kujifunza</div>
                    <a href="{{ route('learning.index') }}" class="dash-nav-link">
                        <div class="dash-nav-link-left">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Kozi za Hadithi</span>
                        </div>
                    </a>
                    <a href="{{ route('learning.quiz') }}" class="dash-nav-link">
                        <div class="dash-nav-link-left">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Maswali ya Quiz</span>
                        </div>
                    </a>
                </div>

                <div class="dash-nav-group">
                    <div class="dash-nav-header">Akaunti</div>
                    @if($user->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="dash-nav-link" style="color: var(--gold);">
                            <div class="dash-nav-link-left">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <span>Jopo la Admin</span>
                            </div>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dash-nav-link" style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left;">
                            <div class="dash-nav-link-left">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span>Toka kwenye Akaunti</span>
                            </div>
                        </button>
                    </form>
                </div>

                <a href="{{ route('library') }}" class="dash-back-link">
                    <span>← Rudi kwenye Maktaba</span>
                </a>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <main class="dash-main">

                <!-- Top Fast Search Bar -->
                <form action="{{ route('library') }}" method="GET" class="dash-search-box">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="var(--gold)" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" name="q" placeholder="Tafuta hadithi, maneno, milango au namba..." class="dash-search-input">
                    <button type="submit" class="dash-search-btn">Tafuta</button>
                </form>

                <!-- Welcome Hero Card -->
                <div class="dash-hero-card">
                    <div>
                        <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gold); margin-bottom: 4px;">
                            • Dashibodi ya Msomaji
                        </div>
                        <h1 class="dash-hero-title">Habari, {{ $user->name }}</h1>
                        <p class="dash-hero-sub">
                            Umekamilisha na kujifunza <strong>{{ $stats['learned'] }} hadithi</strong> hadi sasa, ukiwa na mfululizo wa <strong>siku {{ $stats['streak'] }}</strong>. Endelea kukuza elimu yako ya Sunnah za Mtume ﷺ.
                        </p>
                    </div>
                    <div class="dash-hero-actions">
                        <a href="{{ route('learning.index') }}" class="dash-btn-primary">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            <span>Endelea Kusoma</span>
                        </a>
                        <a href="{{ route('hadith.today') }}" class="dash-btn-secondary">
                            <span>Hadithi ya Leo</span>
                        </a>
                    </div>
                </div>

                <!-- 4 KPI Metrics Grid -->
                <div class="dash-stats-grid">
                    <!-- Metric 1 -->
                    <div class="dash-stat-card">
                        <div class="dash-stat-label">Hadithi Nilizosoma</div>
                        <div class="dash-stat-val-row">
                            <span class="dash-stat-val">{{ $stats['learned'] }}</span>
                            <span class="dash-stat-badge">+{{ $monthCount }} mwezi huu</span>
                        </div>
                        <div class="dash-stat-bar">
                            <div class="dash-stat-bar-fill" style="width: {{ min(100, max(8, ($stats['learned'] > 0 ? ($stats['learned'] / 50) * 100 : 0))) }}%;"></div>
                        </div>
                    </div>

                    <!-- Metric 2 -->
                    <div class="dash-stat-card">
                        <div class="dash-stat-label">Vipendwa (Bookmarks)</div>
                        <div class="dash-stat-val-row">
                            <span class="dash-stat-val">{{ $stats['bookmarks'] }}</span>
                            <span class="dash-stat-badge">Zimehifadhiwa</span>
                        </div>
                        <div class="dash-stat-bar">
                            <div class="dash-stat-bar-fill" style="width: {{ min(100, max(8, ($stats['bookmarks'] > 0 ? ($stats['bookmarks'] / 30) * 100 : 0))) }}%;"></div>
                        </div>
                    </div>

                    <!-- Metric 3 -->
                    <div class="dash-stat-card">
                        <div class="dash-stat-label">Kadi za Kuhifadhi</div>
                        <div class="dash-stat-val-row">
                            <span class="dash-stat-val">{{ $stats['cards'] }}</span>
                            <span class="dash-stat-badge">{{ $stats['due'] }} za leo</span>
                        </div>
                        <div class="dash-stat-bar">
                            <div class="dash-stat-bar-fill" style="width: {{ min(100, max(8, ($stats['cards'] > 0 ? ($stats['cards'] / 20) * 100 : 0))) }}%;"></div>
                        </div>
                    </div>

                    <!-- Metric 4 -->
                    <div class="dash-stat-card">
                        <div class="dash-stat-label">Mfululizo (Streak)</div>
                        <div class="dash-stat-val-row">
                            <span class="dash-stat-val">{{ $stats['streak'] }}</span>
                            <span class="dash-stat-badge">🔥 Siku {{ $stats['streak'] }}</span>
                        </div>
                        <div class="dash-stat-bar">
                            <div class="dash-stat-bar-fill" style="width: {{ min(100, max(8, ($stats['streak'] > 0 ? ($stats['streak'] / 7) * 100 : 0))) }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Two-Column Grid: Recent Readings & Monthly Goals -->
                <div class="dash-columns-grid">

                    <!-- Left: Endelea Kusoma -->
                    <div class="dash-card">
                        <div class="dash-card-header">
                            <div>
                                <h2 class="dash-card-title">{{ $isStarterRecent ? 'Anza Kusoma Hadithi' : 'Endelea Kusoma' }}</h2>
                                <p class="dash-card-desc">{{ $isStarterRecent ? 'Chagua hadithi ya kuanza safari yako ya kujifunza.' : 'Unaanzia pale ulipoishia kusoma hadithi.' }}</p>
                            </div>
                            <a href="{{ route('learning.index') }}" class="dash-card-link">Tazama zote →</a>
                        </div>

                        <div class="dash-item-list">
                            @foreach($recent as $h)
                                @php
                                    $col = $h->chapter?->book?->collection;
                                    $letter = mb_strtoupper(mb_substr($col?->name ?? 'H', 0, 1));
                                @endphp
                                <div class="dash-read-item">
                                    <div class="dash-item-left">
                                        <div class="dash-item-avatar">{{ $letter }}</div>
                                        <div class="dash-item-info">
                                            <div class="dash-item-meta">
                                                <span>{{ $col?->name ?? 'Hadithi' }}</span>
                                                <span>•</span>
                                                <span>Na. {{ $h->number }}</span>
                                            </div>
                                            <h3 class="dash-item-title" title="{{ $h->display_text }}">
                                                {{ \Illuminate\Support\Str::limit($h->display_text, 70) }}
                                            </h3>
                                        </div>
                                    </div>
                                    <a href="{{ route('learning.lesson', $h) }}" class="dash-item-btn">
                                        {{ $isStarterRecent ? 'Anza' : 'Endelea' }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right: Lengo la Mwezi & Mfululizo -->
                    <div class="dash-card" style="display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div class="dash-card-header">
                                <div>
                                    <h2 class="dash-card-title">Lengo la Mwezi</h2>
                                    <p class="dash-card-desc">Hadithi {{ $monthCount }} kati ya lengo la {{ $monthGoal }}.</p>
                                </div>
                            </div>

                            <!-- Circular Progress Ring -->
                            <div class="dash-goal-ring-wrap">
                                @php
                                    $pct = $monthGoal > 0 ? min(1, $monthCount / $monthGoal) : 0;
                                    $circ = 2 * 3.14159 * 42;
                                    $offset = $circ * (1 - $pct);
                                @endphp
                                <div class="dash-goal-box">
                                    <svg class="dash-goal-svg" viewBox="0 0 100 100">
                                        <circle class="dash-goal-bg" cx="50" cy="50" r="42"></circle>
                                        <circle class="dash-goal-fill" cx="50" cy="50" r="42" style="stroke-dasharray: {{ $circ }}; stroke-dashoffset: {{ $offset }};"></circle>
                                    </svg>
                                    <div class="dash-goal-content">
                                        <div class="dash-goal-num">{{ $monthCount }}/{{ $monthGoal }}</div>
                                        <div class="dash-goal-lbl">Hadithi</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Streak Tracker -->
                            <div class="dash-streak-container">
                                <div class="dash-streak-header">
                                    <span style="color: var(--text-primary); display: flex; align-items: center; gap: 4px;">
                                        🔥 Siku {{ $stats['streak'] }} Mfululizo
                                    </span>
                                    <span style="color: var(--gold); font-size: 11px;">Wiki Hii</span>
                                </div>
                                <div class="dash-days-row">
                                    @foreach($weekDays as $day)
                                        <div class="dash-day-pill {{ $day['active'] ? 'active' : '' }} {{ $day['is_today'] ? 'today' : '' }}" title="{{ $day['name'] }} ({{ $day['date'] }})">
                                            {{ $day['letter'] }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Quiz Action Button -->
                        <div style="margin-top: 18px;">
                            <a href="{{ route('learning.quiz') }}" class="dash-btn-secondary" style="width: 100%; justify-content: center; font-size: 12.5px;">
                                <span>Fanya Maswali ya Quiz</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: Unaweza Kupenda (Recommendations) -->
                @if($recommendations->isNotEmpty())
                    <div class="dash-card">
                        <div class="dash-card-header">
                            <div>
                                <h2 class="dash-card-title">Unaweza Kupenda</h2>
                                <p class="dash-card-desc">Mapendekezo ya hadithi kulingana na makusanyo ya Sunnah na mada mbalimbali.</p>
                            </div>
                            <span style="font-size: 11.5px; color: var(--gold); font-weight: 700;">Imependekezwa</span>
                        </div>

                        <div class="dash-recom-grid">
                            @foreach($recommendations as $rec)
                                @php
                                    $col = $rec->chapter?->book?->collection;
                                @endphp
                                <div class="dash-recom-card">
                                    <div style="min-width: 0; flex: 1;">
                                        <div class="dash-recom-meta">
                                            <span>{{ $col?->name ?? 'Hadithi' }}</span>
                                            <span>•</span>
                                            <span>Na. {{ $rec->number }}</span>
                                        </div>
                                        <h4 class="dash-recom-title">
                                            {{ $rec->chapter?->title_sw ?: 'Somo la Hadithi' }}
                                        </h4>
                                        <p class="dash-recom-excerpt">
                                            {{ \Illuminate\Support\Str::limit($rec->display_text, 130) }}
                                        </p>
                                    </div>
                                    <a href="{{ route('hadith.show', $rec) }}" class="dash-recom-btn">
                                        Soma
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </main>
        </div>
    </div>
</x-layouts.app>
