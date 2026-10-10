<?php

return [
    'python' => env('TTS_PYTHON', base_path('.venv-tts/bin/python')),
    // Disk ya MP3: 'hadith-audio-3' (inayowekwa na Laravel Cloud) au 'private'
    'disk' => env('SPEECH_DISK', 'hadith-audio-3'),
    'voices' => [
        'sw' => 'sw-TZ-DaudiNeural',
        'ar' => env('TTS_VOICE_AR', 'ar-EG-ShakirNeural'),
        'en' => 'en-US-GuyNeural',
    ],
];
