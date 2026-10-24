<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use App\Services\HadithAudio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sauti ya hadith (Mfumo mseto: Studio Audio ya Kiarabu + Neural TTS ya Kiume kwa edge-tts).
 *
 * 1. Kwa Kiarabu: Kagua kwanza rekodi halisi ya studio (Sheikh); isipokuwepo, tumia Neural TTS (ar-SA-HamedNeural).
 * 2. Kwa Kiswahili: Tumia Neural TTS (sw-TZ-DaudiNeural).
 * 3. Hifadhi MP3 kwenye disk kwa ajili ya majibu ya papo hapo (0ms).
 * 4. Rudisha MP3 yenye HTTP Range (206) kwa Safari/iPhone na Android.
 */
class HadithAudioController extends Controller
{
    public function __invoke(Request $request, Hadith $hadith, string $lang, HadithAudio $audio): Response
    {
        abort_unless($hadith->is_published, 404);
        abort_unless(in_array($lang, HadithAudio::LANGS, true), 400);
        abort_if($audio->text($hadith, $lang) === '', 404, 'Maandishi ya lugha hii hayapo.');

        try {
            $audioData = $audio->getAudio($hadith, $lang);
        } catch (\Throwable $exception) {
            report($exception);
            abort(502, 'Sauti ya hadithi haipatikani kwa sasa. Jaribu tena baadaye.');
        }

        return $this->mp3Response($request, $audioData['mp3'], [
            'X-Audio-Source' => $audioData['source'],
            'X-Audio-Reciter' => $audioData['reciter'],
        ]);
    }

    /**
     * Taarifa za vyanzo vya sauti vya hadith kwa ajili ya frontend UI.
     */
    public function info(Hadith $hadith, HadithAudio $audio): JsonResponse
    {
        abort_unless($hadith->is_published, 404);

        $hasStudio = ! empty($hadith->getStudioAudioUrl());

        return response()->json([
            'id' => $hadith->id,
            'reference' => $hadith->reference,
            'has_studio' => $hasStudio,
            'sources' => [
                'ar' => [
                    'url' => route('hadith.audio', ['hadith' => $hadith, 'lang' => 'ar']),
                    'type' => $hasStudio ? 'studio' : 'neural',
                    'label' => $hasStudio ? 'Kisomo Halisi cha Studio (Sheikh)' : 'Kisomo cha Kiarabu (Hamed Neural)',
                ],
                'sw' => [
                    'url' => route('hadith.audio', ['hadith' => $hadith, 'lang' => 'sw']),
                    'type' => 'neural',
                    'label' => 'Kisomo cha Kiswahili (Daudi Neural)',
                ],
            ],
        ]);
    }

    /** MP3 yenye Content-Length na HTTP Range (206) — Safari haitasogeza audio bila hii. */
    private function mp3Response(Request $request, string $bytes, array $extraHeaders = []): Response
    {
        $size = strlen($bytes);
        $headers = [
            'Content-Type' => 'audio/mpeg',
            'Content-Disposition' => 'inline',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'private, max-age=86400',
        ] + $extraHeaders;

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
