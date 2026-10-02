# Hadith — Maktaba ya elimu

Project mpya ya kusoma na kutafuta Sahih al-Bukhari na Sahih Muslim kwa Kiarabu na Kiswahili. Laravel 12, Livewire 4, MySQL/MariaDB na CSS ya moja kwa moja; hakuna Node/Vite build inayohitajika kwa kurasa hizi.

## Kuendesha

```sh
cd /Users/apple/Desktop/LEARNING/hadith-app
composer install
cp .env.example .env # kwa installation mpya tu; usibadilishe .env iliyopo
php artisan key:generate # kwa installation mpya tu
php artisan migrate --seed
php artisan hadith:import database/data/hadith-starter.json
php artisan serve --host=127.0.0.1 --port=8012
```

Fungua http://127.0.0.1:8012. Project hii sasa inatumia **database `hadith_library` kwenye XAMPP MariaDB 10.4.28**, kupitia driver ya Laravel `mysql`. MariaDB ndiyo server iliyokuwa kwenye XAMPP na iliyochaguliwa kwa app hii; si Oracle MySQL Server.

Muunganisho wa local `.env` unatumia socket `/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock`. Washa MySQL katika XAMPP kabla ya kuendesha app. `.env.example` ina muundo wa TCP kwa MySQL; rekebisha credentials na socket kulingana na mashine yako. PHP 8.2+ yenye pdo_mysql na mbstring inahitajika. SQLite ya awali imeachwa kama ilivyokuwa na haitumiki kuendesha app.

Mwonekano wa **gold na black** uko `public/css/gold-black.css`; picha ya hero imehifadhiwa locally kwenye `public/images/library-gold-black.jpg`. Hakuna hotlink au API ya picha inayohitajika wakati wa kutumia app.

## Vipengele

- Makusanyo mawili, vitabu na milango yenye vichujio vinavyounganishwa.
- Livewire search kwa maandishi ya Kiswahili, Kiarabu, namba na rejea.
- Utafutaji wa Kiarabu hupuuza irabu/tatweel na tofauti za alif. Maandishi asilia hayabadilishwi.
- Matokeo 10 kwa ukurasa; vichujio huhifadhiwa kwenye URL kwa kushiriki.
- Ukurasa wa hadith wenye maandishi kamili, tafsiri na marejeo.
- Responsive CSS, labels, keyboard focus na Kiarabu chenye RTL.
- Import ya JSON yenye validation kabla ya transaction; kurudia reference husasisha rekodi.

## Hali ya data

**Hadith halisi 8 zimeingizwa**, nne kutoka kila mkusanyo. Huu ni mwanzo, si makusanyo kamili.

| Mkusanyo | Namba | Kitabu / mlango |
|---|---|---|
| Bukhari | 10 | 2 / 4 |
| Bukhari | 13 | 2 / 7 |
| Bukhari | 6114 | 78 / 76 |
| Bukhari | 6116 | 78 / 76 |
| Muslim | 47 | 1 / 19 |
| Muslim | 55 | 1 / 23 |
| Muslim | 1907 | 33 / 45 |
| Muslim | 2553 | 45 / 5 |

Maandishi ya Kiarabu na tafsiri za Kiswahili yamenakiliwa bila mabadiliko kutoka API rasmi ya HadeethEnc. Maandishi hayo yana mtindo wa HadeethEnc na si isnadi zote za nakala ya kitabu. Taarifa ya `grade` ni ya HadeethEnc, si hukumu mpya ya app. Majina ya urambazaji ya vitabu/milango kwa Kiswahili yametafsiriwa kwa ajili ya app; namba na majina ya Kiarabu zimelinganishwa na Sunnah.com. Hadeeth ya Muslim 1907 katika HadeethEnc ina pia lafudhi mbadala ya Bukhari, ambayo imehifadhiwa kama ilivyo.

Dataset: `database/data/hadith-starter.json`. Majibu asilia ya API pamoja na metadata yake yamehifadhiwa `database/data/sources/`; manifest ina URL, tarehe na SHA-256 ya kila response. Hakuna jina la mtafsiri binafsi lililotajwa katika API, hivyo attribution inasema hivyo wazi. [Maelezo ya vyanzo](docs/SOURCES.md).

```sh
# Kujenga JSON tena kutoka snapshots zilizohakikiwa, bila network:
python3 scripts/build-starter-dataset.py
php artisan hadith:import database/data/hadith-starter.json
```

Usitumie snapshot ya zamani kudai tarehe mpya ya kupakuliwa. Kupata toleo jipya: pakua response mpya kutoka URL iliyo kwenye manifest, linganisha maudhui/rejea, sasisha manifest na metadata ya ulinganisho, kisha jenga na import tena. Hii si sync ya kiotomatiki.

### Kuongeza hadith mpya (HadeethEnc + namba rasmi)

Inahitaji mtandao kwa hatua ya kwanza tu. Endesha kwenye Terminal ya Mac:

```sh
python3 scripts/discover-hadeethenc.py --max-requests 500   # rudia hadi iseme "Kazi imekamilika"
python3 scripts/import-discovered.py --dry-run              # angalia kwanza
python3 scripts/import-discovered.py                        # ongeza zenye uhakika
php artisan hadith:import database/data/hadith-starter.json
php artisan hadith:audio-warm
```

Kwa hadith zilizopo zenye namba ya muda (`HE-xxxx`): `python3 scripts/assign-official-numbers.py --dry-run`, kisha bila `--dry-run`. Nakala za hadith moja hufichwa (`is_published: false`), na zenye shaka huenda `discovery/numbering-review.json`.

`import-discovered.py` hufanya kazi bila mtandao. Hulinganisha Kiarabu cha kila hadith na nakala kamili za Bukhari, Muslim, Tirmidhi na Abu Dawud (fawazahmed0/hadith-api, `database/data/discovery/editions/`) kwa jozi za maneno, kisha huweka namba rasmi (mf. Muslim 433). Zenye alama ≥ 0.70 tu ndizo huingizwa; nyingine, pamoja na Ahmad na nakala zilizopo, huenda `database/data/discovery/review.json` kwa ukaguzi wa mkono.

## Kuingiza dataset

Unda faili la JSON lenye array ya objects zenye fields hizi:

| Field | Maana |
|---|---|
| `collection` | `bukhari` au `muslim` |
| `reference` | ID ya kipekee inayoanza na collection, mfano muundo `bukhari:edition:number` |
| `number` | Namba kama string; suffix kama `12a` inaruhusiwa |
| `book` | Object yenye `number` (integer > 0), `title_sw`, `title_ar` |
| `chapter` | Object yenye `number` (integer > 0), `title_sw`, `title_ar` |
| `arabic`, `swahili` | Maandishi kamili yaliyohakikiwa |
| `source_name`, `source_url` | Chanzo cha Kiarabu na HTTP(S) URL |
| `numbering_system` | Toleo/mfumo wa namba |
| `translator`, `translation_source_url` | Mtafsiri na HTTP(S) URL ya chanzo cha tafsiri |
| `license` | Ruhusa/leseni inayoruhusu matumizi haya |
| `reviewed_by`, `reviewed_at` | Mhakiki na tarehe `YYYY-MM-DD`, isiyo ya baadaye |
| `is_published` | Boolean; `false` huficha rekodi kwenye maktaba na URL yake |

```sh
php artisan hadith:import /absolute/path/to/reviewed-hadith.json
```

Import ni ya local CLI pekee. Hakuna upload ya umma au paneli ya admin katika toleo hili. Weka nakala ya database kabla ya kusasisha dataset kubwa. Reference inayorudiwa kwenye faili moja hukataliwa; kwenye import inayofuata husasishwa. Schema ina foreign keys na unique constraints kwa vitabu/milango.

## Majaribio

```sh
php artisan test
```

Tests hutumia SQLite ya memory na hazibadilishi database ya XAMPP. Tests za tabia za app hutumia synthetic fixtures; StarterDatasetTest hukagua hadith halisi dhidi ya majibu asilia ya API, maandishi ya lugha zote mbili, SHA-256 na kutorudia rekodi. Zinajaribu search, filters, pagination, attribution, unpublished visibility na import validation/idempotence.

## Muundo

- `app/Livewire/Library.php`: query, filters, URL state na pagination.
- `resources/views/livewire/library.blade.php`: maktaba.
- `resources/views/hadith.blade.php`: msomaji wa hadith.
- `app/Models/Hadith.php`: search normalization na relationships.
- `app/Console/Commands/ImportHadith.php`: import ya dataset.
- `public/css/app.css`: mwonekano wa desktop na simu.

## Marejeo ya framework

- [Laravel 12](https://laravel.com/docs/12.x)
- [Livewire installation](https://livewire.laravel.com/docs/installation)

Kabla ya kupeleka production: weka APP_DEBUG=false, APP_URL sahihi, HTTPS, backups na ruhusa sahihi za storage. Serve directory ya `public/` pekee.

## Lugha na sauti

Chagua lugha ya maandishi kwenye kichwa cha ukurasa: Kiswahili, English, العربية au Kiswahili pamoja na Kiarabu. Chaguo huhifadhiwa kwenye kifaa. Lebo za urambazaji hubaki Kiswahili. Tafsiri zote za Kiingereza zinatoka HadeethEnc; snapshots na SHA-256 zipo kwenye sources/manifest.json. Utafutaji unajumuisha Kiingereza.

Fungua “Soma / Sikiliza” kisha chagua lugha ya sauti, sauti na kasi. Unaweza kusitisha kwa muda, kuendelea au kuacha. Web Speech API hutumia sauti zilizopo kwenye kifaa/browser; Kiswahili hakipatikani kwenye kila kifaa. Sauti isipopatikana app hutoa maelezo; haisomi maandishi kwa sauti ya lugha tofauti. Sauti ni ya kompyuta, si rekodi ya msomaji. Baadhi ya sauti za kifaa huhitaji mtandao. Hakuna sauti inayocheza yenyewe.

### Sauti za HD (edge-tts) na kuziandaa mapema

MP3 huhifadhiwa kwenye disk ya `private` (local: `storage/app/private`; Laravel Cloud: bucket). Sauti ikikosekana, route huitengeneza mara ya kwanza (sekunde kadhaa). Ili watumiaji wasisubiri, ziandae mapema:

```sh
php artisan hadith:audio-warm --dry-run          # onyesha zinazokosekana
php artisan hadith:audio-warm                    # tengeneza zote (sw, ar, en)
php artisan hadith:audio-warm --lang=ar --limit=50
php artisan hadith:audio-warm --id=12 --force    # tengeneza upya hadith moja
```

Command huruka zilizopo, hivyo inaweza kuendeshwa tena baada ya import au ikikatika. Kwenye player: **Mfululizo** husoma Kiarabu kisha tafsiri, na **Endelea na hadith inayofuata** huenda hadith inayofuata ya mlango (browser ikizuia autoplay, bonyeza ▶).

## Progressive web app

The shared layout includes a web manifest, home-screen icons, and a service worker. No npm build is needed for these public assets. Serve Laravel with `public/` as the web root, over HTTPS (localhost works for development). Keep `sw.js` revalidated rather than serving it with immutable caching.

A supported browser shows **Sakinisha Hadith** when installation is available. On iPhone/iPad, use Safari’s Share → Add to Home Screen. Installation opens Hadith in a standalone window.

After one online visit and service-worker activation, unavailable pages show a self-contained Swahili offline screen with a retry link. This does not download the hadith library: search, bookmarks retrieval, reading pages, and audio still require a connection. Dynamic Laravel/Livewire responses, session tokens, and audio are deliberately not cached.

Verification: open the site on HTTPS or localhost, inspect Application → Manifest and Service Workers in browser developer tools, then switch Network to Offline and reload a page. Restore connectivity and select **Jaribu tena**. Test installation on a supported phone/browser. When changing the offline page, bump `CACHE` in `public/sw.js`; updated workers activate once existing app tabs close.
