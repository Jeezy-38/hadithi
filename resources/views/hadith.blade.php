<x-layouts.app :title="$hadith->chapter->book->collection->name.' · '.$hadith->number">
    <div class="reader-shell">
        <a class="back-link" href="{{ route('library', ['collection' => $hadith->chapter->book->collection->slug, 'book' => $hadith->chapter->book_id, 'chapter' => $hadith->chapter_id]) }}">← Rudi kwenye mlango</a>
        <div class="reader-heading">
            <span class="eyebrow">{{ $hadith->chapter->book->collection->name }}</span>
            <h1>Hadith {{ $hadith->number }}</h1>
            @if($hadith->title)<h2 class="reader-title">{{ $hadith->title }}</h2>@endif
            <p>{{ $hadith->chapter->book->title_sw }} · {{ $hadith->chapter->title_sw }}</p>
        </div>
        <div class="reader-actions no-print">
            <button type="button" id="bookmark-toggle" class="text-button" data-hadith-id="{{ $hadith->id }}" aria-pressed="false">☆ Hifadhi kwenye Vipendwa</button>
            <button type="button" id="share-hadith" class="text-button" data-share-text="{{ $hadith->chapter->book->collection->name.' '.$hadith->number.': '.\Illuminate\Support\Str::limit($hadith->swahili, 200) }}" data-share-url="{{ route('hadith.show', $hadith) }}">⧉ Nakili / Shiriki</button>
            <a class="text-button" href="{{ route('bookmarks') }}">Vipendwa vyangu →</a>
        </div>
        <article class="reader-card">
            <section class="audio-player no-print" aria-label="Kusikiliza hadith">
                <h2>Sikiliza hadith</h2>
                <p>Chagua lugha unayoielewa. Hii ni sauti ya kompyuta inayosoma maandishi.</p>
                <div class="audio-options">
                    <label>Lugha ya sauti<select id="audio-language"><option value="sw">Kiswahili</option><option value="en" @disabled(!$hadith->english)>English</option><option value="ar">العربية</option></select></label>
                    <label>Sauti<select id="audio-voice" aria-describedby="audio-status"></select></label>
                    <label>Kasi<select id="audio-rate"><option value="0.75">0.75×</option><option value="1" selected>1×</option><option value="1.25">1.25×</option><option value="1.5">1.5×</option></select></label>
                </div>
                <div class="audio-actions"><button type="button" id="audio-play" disabled>▶ Soma kwa sauti</button><button type="button" id="audio-pause" disabled>Ⅱ Sitisha kwa muda</button><button type="button" id="audio-stop" disabled>■ Acha</button></div>
                <p id="audio-status" role="status" aria-live="polite">Inatafuta sauti za kifaa…</p>
                <noscript>Washa JavaScript ili kutumia sauti na kubadilisha lugha.</noscript>
            </section>
            <section data-reading="ar"><div class="section-label">MAANDISHI YA KIARABU · {{ $hadith->source_name }}</div>
            <p id="text-ar" class="arabic full-text" lang="ar" dir="rtl">{{ $hadith->arabic }}</p></section>
            <section data-reading="sw"><div class="section-label">TAFSIRI YA KISWAHILI</div>
            <p id="text-sw" class="full-text swahili" lang="sw">{{ $hadith->swahili }}</p></section>
            <section data-reading="en"><div class="section-label">ENGLISH TRANSLATION</div>
            @if($hadith->english)<p id="text-en" class="full-text" lang="en">{{ $hadith->english }}</p>
            <p class="source-note">Translation: <a href="https://hadeethenc.com/en/browse/hadith/{{ $hadith->source_record_id }}" target="_blank" rel="noopener noreferrer">HadeethEnc.com ↗</a></p>
            @else<p>Tafsiri ya Kiingereza bado haipatikani.</p>@endif</section>
            @if($hadith->grade)<p class="source-note">Daraja kwa mujibu wa {{ $hadith->source_name }}: <strong>{{ $hadith->grade }}</strong> · {{ $hadith->attribution }}</p>@endif
        </article>
        <section class="source-card" aria-labelledby="source-heading">
            <h2 id="source-heading">Rejea na chanzo</h2>
            <dl>
                <div><dt>Rejea</dt><dd>{{ $hadith->reference }}</dd></div>
                <div><dt>Kitabu / mlango</dt><dd>{{ $hadith->chapter->book->number }} / {{ $hadith->chapter->number }}</dd></div>
                <div><dt>Mfumo wa namba</dt><dd>{{ $hadith->numbering_system }}</dd></div>
                @if($hadith->reference_url)<div><dt>Linganisha rejea</dt><dd><a href="{{ $hadith->reference_url }}" target="_blank" rel="noopener noreferrer">{{ $hadith->chapter->book->collection->name }} {{ $hadith->number }} · Sunnah.com ↗</a></dd></div>@endif
                <div><dt>Chanzo cha Kiarabu</dt><dd><a href="{{ $hadith->source_url }}" target="_blank" rel="noopener noreferrer">{{ $hadith->source_name }}{{ $hadith->source_record_id ? ' #'.$hadith->source_record_id : '' }} ↗</a></dd></div>
                <div><dt>Tafsiri ya Kiswahili</dt><dd><a href="{{ $hadith->translation_source_url }}" target="_blank" rel="noopener noreferrer">{{ $hadith->translator }} ↗</a></dd></div>
                <div><dt>Ruhusa ya matumizi</dt><dd>{{ $hadith->license }} @if($hadith->license_url)<a href="{{ $hadith->license_url }}" target="_blank" rel="noopener noreferrer">Masharti ↗</a>@endif</dd></div>
                @if($hadith->source_fetched_at)<div><dt>Ilipakuliwa</dt><dd>{{ $hadith->source_fetched_at->format('d/m/Y') }}</dd></div>@endif
                <div><dt>Ulinganisho uliorekodiwa</dt><dd>{{ $hadith->reviewed_by }} · {{ $hadith->reviewed_at->format('d/m/Y') }}</dd></div>
            </dl>
            @if($hadith->content_note)<p class="source-note">{{ $hadith->content_note }}</p>@endif
        </section>
        @if($related->isNotEmpty())
        <section class="related-hadith no-print" aria-label="Hadith nyingine za mlango huu">
            <h2>Hadith nyingine za mlango huu</h2>
            <ul>
                @foreach($related as $item)
                    <li><a href="{{ route('hadith.show', $item) }}">Na. {{ $item->number }} — {{ \Illuminate\Support\Str::limit($item->swahili, 90) }}</a></li>
                @endforeach
            </ul>
        </section>
        @endif
    </div>
</x-layouts.app>
