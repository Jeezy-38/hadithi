<?php

namespace App\Livewire;

use App\Models\Hadith;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TopicIndex extends Component
{
    use WithPagination;

    #[Url(as: 'mada', history: true)]
    public string $topic = '';

    #[Url(as: 'q', history: true)]
    public string $search = '';

    public function mount(?string $slug = null): void
    {
        if ($slug && empty($this->topic)) {
            $this->topic = $slug;
        }
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'topic'])) {
            $this->resetPage();
        }
    }

    public function selectTopic(string $slug): void
    {
        $this->topic = ($this->topic === $slug) ? '' : $slug;
        $this->search = '';
        $this->resetPage();
    }

    public function clearTopic(): void
    {
        $this->topic = '';
        $this->search = '';
        $this->resetPage();
    }

    public static function getIslamicTopics(): array
    {
        return [
            'subira' => [
                'slug' => 'subira',
                'title_sw' => 'Subira na Mitihani',
                'title_ar' => 'الصبر والاحتساب عند البلاء',
                'title_en' => 'Patience & Trials',
                'desc' => 'Mafundisho ya Mtume ﷺ kuhusu kuwa na subira, utulivu wa moyo na matumaini wakati wa magumu na mitihani ya maisha.',
                'badge' => 'Subira',
                'icon' => 'sun',
                'color' => '#EBB036',
                'terms' => ['subira', 'mitihani', 'subiri', 'bala', 'الصبر', 'patience'],
            ],
            'tabia-njema' => [
                'slug' => 'tabia-njema',
                'title_sw' => 'Tabia Njema na Maadili',
                'title_ar' => 'حسن الخلق ومكارم الأخلاق',
                'title_en' => 'Good Manners & Akhlaq',
                'desc' => 'Mwongozo wa ustaarabu, upole, ukarimu, unyenyekevu na tabia njema anazopaswa kuwa nazo muumini.',
                'badge' => 'Maadili',
                'icon' => 'heart',
                'color' => '#10B981',
                'terms' => ['tabia', 'maadili', 'adabu', 'wema', 'upole', 'الأدب', 'الأخلاق', 'manners'],
            ],
            'imani' => [
                'slug' => 'imani',
                'title_sw' => 'Tawhid na Misingi ya Imani',
                'title_ar' => 'التوحيد وأركان الإيمان',
                'title_en' => 'Faith & Monotheism',
                'desc' => 'Kumpwekesha Mwenyezi Mungu, kumtegemea Yeye pekee (Tawakkul) na kushikamana na nguzo kuu za Imani.',
                'badge' => 'Tawhid',
                'icon' => 'star',
                'color' => '#3B82F6',
                'terms' => ['imani', 'tawhid', 'mwenyezi mungu', 'allah', 'pekee', 'التوحيد', 'الإيمان', 'faith'],
            ],
            'swala' => [
                'slug' => 'swala',
                'title_sw' => 'Swala, Tohara na Ibada',
                'title_ar' => 'الصلاة والطهارة والخشوع',
                'title_en' => 'Prayer & Purification',
                'desc' => 'Nguzo ya pili ya Uislamu, nyakati za swala, tohara na udhu, na umuhimu wa swala za jamaa na sunnah.',
                'badge' => 'Swala',
                'icon' => 'compass',
                'color' => '#0D9488',
                'terms' => ['swala', 'kuswali', 'adhana', 'tohara', 'udhu', 'الصلاة', 'الوضوء', 'prayer'],
            ],
            'tawba' => [
                'slug' => 'tawba',
                'title_sw' => 'Tawba na Msamaha wa Allah',
                'title_ar' => 'التوبة والاستغفار وسعة المغفرة',
                'title_en' => 'Repentance & Forgiveness',
                'desc' => 'Kurejea kwa Mola Mlezi, toba ya kweli, kuomba maghfirah na rehema zisizo na kikomo za Allah kwa waja Wake.',
                'badge' => 'Maghfirah',
                'icon' => 'refresh',
                'color' => '#8B5CF6',
                'terms' => ['tawba', 'toba', 'msamaha', 'kusamehe', 'istighfar', 'dhambi', 'التوبة', 'الاستغفار', 'forgiveness'],
            ],
            'familia' => [
                'slug' => 'familia',
                'title_sw' => 'Familia, Ndoa na Haki za Wazazi',
                'title_ar' => 'بر الوالدين وحسن المعاشرة والأسرة',
                'title_en' => 'Family, Parents & Marriage',
                'desc' => 'Kuwatendea wema wazazi wawili, mapenzi na heshima katika ndoa, na malezi bora ya watoto katika jamii.',
                'badge' => 'Familia',
                'icon' => 'users',
                'color' => '#EC4899',
                'terms' => ['wazazi', 'mama', 'baba', 'ndoa', 'mke', 'mume', 'watoto', 'familia', 'الوالدين', 'النكاح', 'family'],
            ],
            'riziki' => [
                'slug' => 'riziki',
                'title_sw' => 'Riziki Halali na Kazi',
                'title_ar' => 'الرزق الحلال والصدق في المعاملات',
                'title_en' => 'Halal Earning & Work',
                'desc' => 'Kutafuta chumo halali kwa jasho lako, uaminifu katika biashara, na kujiepusha na riba, rushwa au haramu.',
                'badge' => 'Riziki',
                'icon' => 'briefcase',
                'color' => '#F59E0B',
                'terms' => ['riziki', 'biashara', 'kazi', 'halali', 'chuma', 'dhuluma', 'الرزق', 'البيوع', 'halal'],
            ],
            'dua-dhikri' => [
                'slug' => 'dua-dhikri',
                'title_sw' => 'Dua, Dhikri na Utulivu wa Moyo',
                'title_ar' => 'فضل الذكر والدعاء وطمأنينة القلوب',
                'title_en' => 'Supplication & Dhikr',
                'desc' => 'Fadhila za kumkumbuka Allah asubuhi na jioni, nyakati teule za kujibiwa dua na nguvu ya dhikri katika roho.',
                'badge' => 'Dhikri',
                'icon' => 'sparkles',
                'color' => '#06B6D4',
                'terms' => ['dua', 'dhikri', 'kumkumbuka', 'kuomba', 'subhanallah', 'alhamdulillah', 'الدعاء', 'الذكر', 'supplication'],
            ],
            'udugu' => [
                'slug' => 'udugu',
                'title_sw' => 'Kupendana kwa Ajili ya Allah na Udugu',
                'title_ar' => 'الأخوة والمحبة في الله ونبذ الفرقة',
                'title_en' => 'Islamic Brotherhood',
                'desc' => 'Kushikamana kwa waumini kama mwili mmoja, kuamkiana kwa amani, na kupendana kwa dhati kwa ajili ya Mwenyezi Mungu.',
                'badge' => 'Udugu',
                'icon' => 'shield',
                'color' => '#6366F1',
                'terms' => ['udugu', 'kupendana', 'ndugu', 'amani', 'salamu', 'muumini', 'الأخوة', 'المحبة', 'brotherhood'],
            ],
            'rehema' => [
                'slug' => 'rehema',
                'title_sw' => 'Sadaka, Huruma na Wema',
                'title_ar' => 'الصدقة والرحمة بالضعفاء والمساكين',
                'title_en' => 'Charity & Compassion',
                'desc' => 'Kutoa mali kwa siri na dhahiri, kusaidia mayatima na masikini, na fadhila kubwa za sadaka inayodumu.',
                'badge' => 'Sadaka',
                'icon' => 'gift',
                'color' => '#14B8A6',
                'terms' => ['sadaka', 'rehema', 'huruma', 'kusaidia', 'yatima', 'masikini', 'zaka', 'الصدقة', 'الرحمة', 'charity'],
            ],
            'ulimi' => [
                'slug' => 'ulimi',
                'title_sw' => 'Kuhifadhi Ulimi na Kudhibiti Hasira',
                'title_ar' => 'حفظ اللسان والنهي عن الغضب والكذب',
                'title_en' => 'Guarding the Tongue & Anger',
                'desc' => 'Tahadhari dhidi ya usengenyaji, uongo, kashfa, matusi na jinsi ya kudhibiti hisia za hasira wakati wa ghadhabu.',
                'badge' => 'Ulimi',
                'icon' => 'message',
                'color' => '#EF4444',
                'terms' => ['ulimi', 'hasira', 'kusengenya', 'uongo', 'kashfa', 'matusi', 'ghadhabu', 'اللسان', 'الغضب', 'tongue'],
            ],
            'pepo-akhera' => [
                'slug' => 'pepo-akhera',
                'title_sw' => 'Pepo, Akhera na Siku ya Mwisho',
                'title_ar' => 'وصف الجنة واليوم الآخر والترغيب في الخير',
                'title_en' => 'Paradise & The Hereafter',
                'desc' => 'Sifa za Pepo (Jannah), neema zake za milele, kujiandaa na safari ya Akhera, na kuepuka adhabu ya Moto.',
                'badge' => 'Jannah',
                'icon' => 'crown',
                'color' => '#EBB036',
                'terms' => ['pepo', 'jannah', 'akhera', 'kiyama', 'moyo', 'kaburi', 'kifo', 'الجنة', 'الآخرة', 'paradise'],
            ],
        ];
    }

    public function render()
    {
        $allTopics = self::getIslamicTopics();
        $selectedTopic = $this->topic !== '' && isset($allTopics[$this->topic]) ? $allTopics[$this->topic] : null;

        $hadiths = null;
        if ($selectedTopic) {
            $query = Hadith::published()->with('chapter.book.collection');
            $terms = $selectedTopic['terms'];

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->orWhere('swahili', 'like', "%{$term}%")
                      ->orWhere('arabic', 'like', "%{$term}%")
                      ->orWhere('english', 'like', "%{$term}%");
                }
            });

            if (trim($this->search) !== '') {
                $query->search(mb_substr($this->search, 0, 200));
            }

            $hadiths = $query->paginate(15);
        }

        return view('livewire.topic-index', [
            'topics' => $allTopics,
            'selectedTopic' => $selectedTopic,
            'hadiths' => $hadiths,
        ])->layout('components.layouts.app', [
            'title' => $selectedTopic ? ($selectedTopic['title_sw'] . ' · Mada za Hadithi') : 'Mada za Kiislamu · Bayt Al-Hadith',
        ]);
    }
}
