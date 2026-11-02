<x-layouts.app :noindex="true" title="Wasifu Wangu · Bayt Ul Hadith">
    <div class="reader-shell lc-shell pf-shell">
        <a class="back-link" href="{{ route('library') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span data-i18n="pg_lc_back">Nyuma</span>
        </a>

        <section class="lc-card pf-head">
            @if($user->avatar)
                <img class="pf-avatar" src="{{ $user->avatar }}" alt="" referrerpolicy="no-referrer">
            @else
                <span class="pf-avatar pf-initial" aria-hidden="true">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
            @endif
            <div class="pf-id">
                <span class="eyebrow"><span class="eyebrow-dot"></span> <b data-i18n="pg_pf_eyebrow">AKAUNTI</b></span>
                <h1 class="pf-name">{{ $user->name }}</h1>
                @if($user->email)<p class="pf-email">{{ $user->email }}</p>@endif
                <ul class="pf-badges">
                    @if($user->isAdmin())<li class="pf-badge pf-badge-admin">Admin</li>@endif
                    @if($user->isSupporter())<li class="pf-badge pf-badge-gold">★ {{ $user->supporterBadge() }}</li>@endif
                    @if($user->email_verified_at)<li class="pf-badge" data-i18n="pg_pf_verified">Imethibitishwa</li>@endif
                </ul>
                <p class="pf-since" data-i18n="pg_pf_since" data-i18n-vars="{{ json_encode(['d' => $user->created_at->format('d/m/Y')]) }}">Mwanachama tangu {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </section>

        <h2 class="lc-section-title" data-i18n="pg_pf_stats">Maendeleo yako</h2>
        <div class="lc-stats pf-stats">
            <div class="lc-stat"><strong>{{ $stats['streak'] }}</strong><span data-i18n="pg_lc_streak">Siku mfululizo</span></div>
            <div class="lc-stat"><strong>{{ $stats['learned'] }}</strong><span data-i18n="pg_lc_learned">Masomo yaliyokamilika</span></div>
            <div class="lc-stat"><strong>{{ $stats['due'] }}</strong><span data-i18n="pg_lc_due">Kadi za kurudia leo</span></div>
            <div class="lc-stat"><strong>{{ $stats['cards'] }}</strong><span data-i18n="pg_pf_cards">Kadi za kuhifadhi</span></div>
            <div class="lc-stat"><strong>{{ $stats['bookmarks'] }}</strong><span data-i18n="pg_pf_bookmarks">Vipendwa</span></div>
        </div>

        <div class="lc-actions">
            <a class="lc-action" href="{{ route('learning.index') }}">
                <span class="lc-action-title" data-i18n="pg_pf_continue">Endelea kujifunza</span>
            </a>
            <a class="lc-action" href="{{ route('bookmarks') }}">
                <span class="lc-action-title" data-i18n="pg_pf_open_bookmarks">Fungua vipendwa</span>
            </a>
            @if($user->isAdmin())
                <a class="lc-action" href="{{ route('admin.dashboard') }}">
                    <span class="lc-action-title" data-i18n="pg_pf_admin">Jopo la Utawala</span>
                </a>
            @endif
        </div>

        <h2 class="lc-section-title" data-i18n="pg_pf_recent">Masomo ya hivi karibuni</h2>
        @if($recent->isEmpty())
            <p class="lc-note"><span data-i18n="pg_pf_recent_empty">Bado hujakamilisha somo lolote. Anza sasa!</span></p>
        @else
            <ul class="pf-recent">
                @foreach($recent as $h)
                    <li>
                        <a href="{{ route('learning.lesson', $h) }}">
                            <span class="pf-recent-title">{{ $h->title ?: \Illuminate\Support\Str::limit($h->swahili, 80) }}</span>
                            <span class="pf-recent-no">Na. {{ $h->number }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('logout') }}" class="pf-logout">
            @csrf
            <button type="submit" class="lc-btn lc-btn-ghost" data-i18n="auth_logout">Toka</button>
        </form>
    </div>
</x-layouts.app>
