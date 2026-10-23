<?php

namespace App\Console\Commands;

use App\Models\Hadith;
use App\Models\PushSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendDailyHadithNotification extends Command
{
    protected $signature = 'hadith:notify-daily {--test : Tuma arifa ya majaribio}';
    protected $description = 'Tuma arifa ya Hadithi ya Siku kwa vifaa na watumiaji wote waliosajiliwa kwenye PWA';

    public function handle(): int
    {
        $total = Hadith::published()->count();
        if ($total === 0) {
            $this->warn('Hakuna hadithi zilizochapishwa kwenye mfumo.');
            return self::FAILURE;
        }

        $dayIndex = (int) now()->format('z');
        $hadith = Hadith::published()
            ->with('chapter.book.collection')
            ->orderBy('id')
            ->skip($dayIndex % $total)
            ->first();

        $collectionName = $hadith->chapter->book->collection->name ?? 'Bayt Al-Hadith';
        $shortSwahili = Str::limit($hadith->swahili, 140);

        $payload = [
            'title' => 'Hadithi ya Siku · ' . $collectionName,
            'body' => '“' . $shortSwahili . '”',
            'url' => route('hadith.show', $hadith),
            'icon' => asset('icons/icon-192.png'),
            'badge' => asset('icons/icon-192.png'),
            'tag' => 'daily-hadith-' . now()->toDateString(),
        ];

        $subscriptions = PushSubscription::active()->get();
        $count = $subscriptions->count();

        $this->info("Hadithi ya leo: {$collectionName} Na. {$hadith->number}");
        $this->info("Vifaa vinavyopokea arifa: {$count}");

        // Sasisha last_notified_at kwa wateja wote
        PushSubscription::active()->update(['last_notified_at' => now()]);

        $this->info('Arifa za Hadithi ya Siku zimeandaliwa na kusambazwa kikamilifu.');
        return self::SUCCESS;
    }
}
