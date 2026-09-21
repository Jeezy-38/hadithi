<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Soma na tafuta hadith za Sahih al-Bukhari na Sahih Muslim kwa Kiarabu na Kiswahili.">
    <title>{{ $title ?? 'Maktaba ya Hadith' }} · Hadith</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gold-black.css') }}">
    @livewireStyles
</head>
<body>
    <a class="skip-link" href="#main">Ruka kwenda maudhui</a>
    <header class="site-header">
        <a href="{{ route('library') }}" class="brand" aria-label="Hadith — ukurasa wa mwanzo"><img src="{{ asset('images/logo.png') }}" alt="" class="brand-logo"><span>Hadith<small>MAKTABA YA ELIMU</small></span></a>
        <nav aria-label="Urambazaji mkuu"><a href="{{ route('library') }}">Maktaba</a><a href="{{ route('hadith.today') }}">Hadith ya leo</a><a href="{{ route('bookmarks') }}">Vipendwa</a><label class="language">Lugha ya hadith
            <select id="reading-language" aria-label="Lugha ya kusoma hadith">
                <option value="both">Kiswahili + العربية</option><option value="sw">Kiswahili</option><option value="en">English</option><option value="ar">العربية</option>
            </select></label></nav>
    </header>
    <main id="main">{{ $slot }}</main>
    <footer class="site-footer"><span class="footer-brand">Hadith <span>·</span> Maktaba ya elimu</span><span>Kiarabu na Kiswahili · Rejea katika kila hadith</span></footer>
    <script src="{{ asset('js/reader.js') }}" defer></script>
    @livewireScripts
</body>
</html>
