@php
    $sponsor = \Illuminate\Support\Facades\Cache::remember('sponsor:main:'.now()->toDateString(), 600, fn () => \App\Models\Sponsor::main());
@endphp
@if ($sponsor)
    <div class="sponsor-strip-wrap">
        <a class="rawdah-sponsor sponsor-strip" href="{{ route('sponsors.go', $sponsor) }}" target="_blank" rel="sponsored noopener">
            <span class="rawdah-sponsor-label" data-i18n="pg_sp_main">Mdhamini Mkuu</span>
            @if ($sponsor->logo_url)
                <img class="rawdah-sponsor-logo" src="{{ $sponsor->logo_url }}" alt="" loading="lazy" width="28" height="28">
            @else
                <span class="rawdah-sponsor-logo rawdah-sponsor-initial" aria-hidden="true">{{ $sponsor->initial }}</span>
            @endif
            <span class="rawdah-sponsor-name">{{ $sponsor->name }}</span>
            @if ($sponsor->tagline)<span class="rawdah-sponsor-tag">— {{ $sponsor->tagline }}</span>@endif
        </a>
    </div>
@endif
