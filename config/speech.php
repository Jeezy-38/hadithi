<?php

return [
    'python' => env('TTS_PYTHON', base_path('.venv-tts/bin/python')),
    // Disk ya MP3: 'private' (Laravel Cloud huiwekea bucket; local ni storage/app/private).
    'disk' => env('SPEECH_DISK', 'private'),
    'voices' => [
        'sw' => 'sw-TZ-DaudiNeural',
        'ar' => 'ar-SA-HamedNeural',
        'en' => 'en-US-GuyNeural',
    ],
];
