@php
    $cfg = config('sponsors');
    $wa = 'https://wa.me/'.$cfg['whatsapp'].'?text=';
    $waGeneral = $wa.rawurlencode('Habari Bayt Ul Hadith, napenda kujua kuhusu kudhamini / kutangaza kwenye tovuti yenu.');
@endphp
<x-layouts.app title="Wadhamini na Matangazo" description="Tangaza biashara, msikiti au taasisi yako kwa maelfu ya wasomaji wa hadith kwenye Bayt Ul Hadith. Vifurushi vya siku, wiki na mwezi." :canonical="route('sponsors')">
    <link rel="stylesheet" href="{{ asset('css/sponsors.css') }}">
    <div class="reader-shell sponsors-page">
        <a class="back-link" href="{{ route('library') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span>Nyuma</span>
        </a>

        <header class="sp-hero">
            <span class="eyebrow"><span class="eyebrow-dot"></span> <b>WADHAMINI</b></span>
            <h1>Fikia wasomaji wa hadith kila siku</h1>
            <p>Tangaza biashara, msikiti, madrasa au huduma yako mbele ya Waislamu wanaosoma na kutafuta hadith kwa Kiswahili. Kila mdhamini anasaidia pia kuendeleza maktaba hii bila malipo kwa wote.</p>
            <a class="sp-btn sp-btn-primary" href="{{ $waGeneral }}" target="_blank" rel="noopener">
                <span>Ongea nasi WhatsApp</span>
            </a>
        </header>

        <section class="sp-grid" aria-label="Vifurushi">
            @foreach ($cfg['packages'] as $p)
                <article class="sp-card {{ $p['featured'] ? 'is-featured' : '' }}">
                    @if ($p['featured'])<span class="sp-ribbon">Maarufu zaidi</span>@endif
                    <h2>{{ $p['name'] }}</h2>
                    <div class="sp-price"><span class="sp-cur">TZS</span> {{ number_format($p['price']) }} <small>/ {{ $p['period'] }}</small></div>
                    <ul class="sp-list">
                        @foreach ($p['features'] as $feat)
                            <li>{{ $feat }}</li>
                        @endforeach
                    </ul>
                    <a class="sp-btn {{ $p['featured'] ? 'sp-btn-primary' : 'sp-btn-ghost' }}" target="_blank" rel="noopener"
                       href="{{ $wa.rawurlencode('Habari Bayt Ul Hadith, napenda kuchukua kifurushi cha '.$p['name'].' (TZS '.number_format($p['price']).' / '.$p['period'].').') }}">
                        Chagua kifurushi hiki
                    </a>
                </article>
            @endforeach
        </section>

        <section class="sp-note">
            <h3>Unahitaji kitu tofauti?</h3>
            <p>Tunaweza kuandaa kifurushi maalum kwa msimu wa Ramadhani, matukio ya msikiti, au kampeni ya taasisi yako. Zingatia: tunakubali matangazo yanayoendana na maadili ya Kiislamu pekee.</p>
            <div class="sp-contact">
                <a href="{{ $waGeneral }}" target="_blank" rel="noopener"><b>WhatsApp:</b> {{ $cfg['whatsapp_display'] }}</a>
                <a href="mailto:{{ $cfg['email'] }}"><b>Barua pepe:</b> {{ $cfg['email'] }}</a>
            </div>
        </section>
    </div>
</x-layouts.app>
