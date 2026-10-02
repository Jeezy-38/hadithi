<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use App\Services\HadithAudio;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sauti ya hadith (TTS ya kiume, edge-tts).
 *
 * 1. Tafuta MP3 kwenye disk (njia mpya yenye fingerprint, kisha ya zamani).
 * 2. Isipokuwepo, tengeneza kwa edge-tts na uihifadhi (au iandae mapema: php artisan hadith:audio-warm).
 * 3. Rudisha MP3 yenye Range (206) ili kusogeza scrubber kufanye kazi, hasa Safari/iPhone.
 */
class HadithAudioController extends Controller
{
    public function __invoke(Request $request, Hadith $hadith, string $lang, HadithAudio $audio): Response
    {
        abort_unless($hadith->is_published, 404);
        abort_unless(in_array($lang, HadithAudio::LANGS, true), 400);
        abort_if($audio->text($hadith, $lang) === '', 404, 'Maandishi ya lugha hii hayapo.');

        if (($mp3 = $audio->cached($hadith, $lang)) === null) {
            try {
                $mp3 = $audio->generate($hadith, $lang);
            } catch (\Throwable $exception) {
                report($exception);
                abort(502, 'Sauti ya kiume haipatikani kwa sasa. Jaribu tena baadaye.');
            }
        }

        return $this->mp3Response($request, $mp3);
    }

    /** MP3 yenye Content-Length na HTTP Range (206) — Safari haitasogeza audio bila hii. */
    private function mp3Response(Request $request, string $bytes): Response
    {
        $size = strlen($bytes);
        $headers = [
            'Content-Type' => 'audio/mpeg',
            'Content-Disposition' => 'inline',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'private, max-age=86400',
        ];

        $range = $request->header('Range');
        if (! $range || ! preg_match('/^bytes=(\d*)-(\d*)$/', trim($range), $m) || ($m[1] === '' && $m[2] === '')) {
            return response($bytes, 200, $headers + ['Content-Length' => $size]);
        }

        if ($m[1] === '') {            // bytes=-500 → baiti 500 za mwisho
            $start = max(0, $size - (int) $m[2]);
            $end = $size - 1;
        } else {
            $start = (int) $m[1];
            $end = $m[2] === '' ? $size - 1 : min((int) $m[2], $size - 1);
        }

        if ($start >= $size || $start > $end) {
            return response('', 416, $headers + ['Content-Range' => "bytes */{$size}"]);
        }

        return response(substr($bytes, $start, $end - $start + 1), 206, $headers + [
            'Content-Range' => "bytes {$start}-{$end}/{$size}",
            'Content-Length' => $end - $start + 1,
        ]);
    }
}
