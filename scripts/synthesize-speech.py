"""Read a speech request from stdin; output MP3 only after synthesis succeeds."""
import asyncio
import io
import json
import sys

import edge_tts
from gtts import gTTS


async def synthesize_edge(text: str, voice: str) -> bytes:
    speech = edge_tts.Communicate(text, voice)
    audio = bytearray()
    async for chunk in speech.stream():
        if chunk['type'] == 'audio':
            audio.extend(chunk['data'])
    if len(audio) < 100:
        raise RuntimeError('No audio returned from edge-tts')
    return bytes(audio)


def synthesize_gtts(text: str, voice: str) -> bytes:
    lang = 'sw'
    if voice.startswith('ar') or voice.startswith('ar-'):
        lang = 'ar'
    elif voice.startswith('en') or voice.startswith('en-'):
        lang = 'en'
    tts = gTTS(text=text, lang=lang)
    fp = io.BytesIO()
    tts.write_to_fp(fp)
    data = fp.getvalue()
    if len(data) < 100:
        raise RuntimeError('No audio returned from gTTS')
    return data


async def main():
    request = json.load(sys.stdin)
    text = request['text']
    voice = request['voice']

    audio = None
    try:
        audio = await asyncio.wait_for(synthesize_edge(text, voice), timeout=3.0)
    except Exception:
        # Fallback to gTTS if edge-tts fails, times out, or returns 403
        audio = synthesize_gtts(text, voice)

    if not audio or len(audio) < 100:
        raise RuntimeError('No audio generated')

    sys.stdout.buffer.write(audio)


if __name__ == '__main__':
    asyncio.run(main())

