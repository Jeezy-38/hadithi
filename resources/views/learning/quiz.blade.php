<x-layouts.app title="Quiz · Learning Centre">
    <div class="reader-shell lc-shell">
        <a class="back-link" href="{{ $book ? route('learning.course', $book->id) : route('learning.index') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span data-i18n="pg_lc_back_courses">Rudi kwenye kozi</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> <b data-i18n="pg_lc_quiz_title">QUIZ</b></span>
            <h1>{{ $book ? $book->title_sw : 'Quiz ya Haraka' }}</h1>
        </div>

        <div class="lc-quiz" data-lc-quiz data-url="{{ route('learning.quiz.data', $book?->id) }}" data-retry="{{ url()->current() }}">
            <p class="lc-hint-text" data-i18n="pg_lc_loading">Inapakia…</p>
        </div>
    </div>
    <script src="{{ asset('js/learning.js') }}?v={{ filemtime(public_path('js/learning.js')) }}" defer></script>
</x-layouts.app>
