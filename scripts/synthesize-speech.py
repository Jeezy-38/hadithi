"""Read a speech request from stdin; output MP3 only after synthesis succeeds."""
import asyncio
import json
import sys

import edge_tts


async def main():
    request = json.load(sys.stdin)
    speech = edge_tts.Communicate(request['text'], request['voice'])
    audio = bytearray()
    async for chunk in speech.stream():
        if chunk['type'] == 'audio':
            audio.extend(chunk['data'])
    if len(audio) < 100:
        raise RuntimeError('No audio returned')
    sys.stdout.buffer.write(audio)


if __name__ == '__main__':
    asyncio.run(main())
