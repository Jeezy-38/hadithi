<x-layouts.app :title="$book->title_sw . ' · Learning Centre'">
    <div class="reader-shell lc-shell">
        <a class="back-link" href="{{ route('learning.index') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span data-i18n="pg_lc_courses">Kozi</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> {{ $book->collection->name }}</span>
            <h1>{{ $book->title_sw }}</h1>
            @auth
                <p data-i18n="pg_lc_progress" data-i18n-vars='{"a":{{ (int) $doneTotal }},"b":{{ (int) $lessons->total() }}}'>{{ $doneTotal }} / {{ $lessons->total() }} yamekamilika</p>
            @else
                <p data-i18n="pg_lc_lessons" data-i18n-vars='{"n":{{ (int) $lessons->total() }}}'>{{ $lessons->total() }} masomo</p>
            @endauth
        </div>

        <div class="lc-actions">
            <a class="lc-action" href="{{ route('learning.quiz', $book->id) }}">
                <span class="lc-action-title" data-i18n="pg_lc_course_quiz">Quiz ya kozi hii</span>
            </a>
        </div>

        <ol class="lc-lessons" start="{{ $lessons->firstItem() }}">
            @foreach($lessons as $h)
                <li>
                    <a class="lc-lesson {{ in_array($h->id, $done, true) ? 'is-done' : '' }}" href="{{ route('learning.lesson', $h) }}">
                        <span class="lc-lesson-no">{{ $h->number }}</span>
                        <span class="lc-lesson-body">
                            <span class="lc-lesson-title">{{ \Illuminate\Support\Str::limit($h->title ?: $h->swahili, 110) }}</span>
                            <span class="lc-lesson-chapter">{{ \Illuminate\Support\Str::limit($h->chapter->title_sw, 80) }}</span>
                        </span>
                        <span class="lc-lesson-check" aria-hidden="true">{{ in_array($h->id, $done, true) ? '✓' : '' }}</span>
                    </a>
                </li>
            @endforeach
        </ol>

        @if($lessons->hasPages())
            <div class="lc-pager">
                @if($lessons->previousPageUrl())
                    <a class="lc-btn" href="{{ $lessons->previousPageUrl() }}" data-i18n="pg_lc_prev_page">← Uliotangulia</a>
                @endif
                @if($lessons->nextPageUrl())
                    <a class="lc-btn" href="{{ $lessons->nextPageUrl() }}" data-i18n="pg_lc_next_page">Unaofuata →</a>
                @endif
            </div>
        @endif
    </div>
</x-layouts.app>
