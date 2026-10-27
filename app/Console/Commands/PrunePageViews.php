<?php

namespace App\Console\Commands;

use App\Models\PageView;
use Illuminate\Console\Command;

class PrunePageViews extends Command
{
    protected $signature = 'app:prune-page-views {--days=90 : Hifadhi ziara za siku ngapi za karibuni}';

    protected $description = 'Futa rekodi za ziara (page_views) za zamani ili jedwali lisikue bila mwisho';

    public function handle(): int
    {
        $days = max(7, (int) $this->option('days'));
        $cutoff = now()->subDays($days)->toDateString();

        $deleted = PageView::where('viewed_date', '<', $cutoff)->delete();

        $this->info("Zimefutwa rekodi {$deleted} za kabla ya {$cutoff}.");

        return self::SUCCESS;
    }
}
