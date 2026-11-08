// Jenga upya faili zilizopunguzwa (.min) baada ya kubadilisha CSS/JS:  node scripts/minify-assets.mjs
// Inahitaji intaneti mara ya kwanza (npx). Tovuti hutumia .min tu ikiwa ni ya sasa kwa chanzo chake.
import { execFileSync } from 'node:child_process';
import { readFileSync, writeFileSync } from 'node:fs';
import { createHash } from 'node:crypto';

const jobs = [
  ['public/css/gold-black.css', 'public/css/gold-black.min.css', 'css'],
  ['public/js/reader.js', 'public/js/reader.min.js', 'js'],
  ['public/js/i18n-pages.js', 'public/js/i18n-pages.min.js', 'js'],
];
for (const [src, out, kind] of jobs) {
  const hash = createHash('md5').update(readFileSync(src)).digest('hex');
  const code = kind === 'css'
    ? execFileSync('npx', ['--yes', 'csso-cli', src, '--no-restructure'], { encoding: 'utf8', maxBuffer: 1 << 28 })
    : execFileSync('npx', ['--yes', 'terser', src, '-c', '-m'], { encoding: 'utf8', maxBuffer: 1 << 28 });
  writeFileSync(out, `/*src:${hash}*/\n${code}`);
  console.log(out, code.length, 'bytes');
}
