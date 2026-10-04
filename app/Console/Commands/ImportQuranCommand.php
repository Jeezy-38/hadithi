<?php

namespace App\Console\Commands;

use App\Services\QuranService;
use Illuminate\Console\Command;

class ImportQuranCommand extends Command
{
    protected $signature = 'quran:import {--surah= : Specific surah number (1-114)} {--all : Import all 114 surahs}';

    protected $description = 'Import or refresh Quran Surahs and Ayahs from the API into the local database';

    public function handle(QuranService $service): int
    {
        $this->info('Anzisha uingizaji wa Qur\'ani Tukufu...');

        $surahOption = $this->option('surah');
        $allOption = $this->option('all');

        if ($surahOption) {
            $num = (int) $surahOption;
            if ($num < 1 || $num > 114) {
                $this->error('Namba ya Sura lazima iwe kati ya 1 na 114.');
                return self::FAILURE;
            }

            $this->info("Inapakua Sura ya {$num}...");
            $success = $service->fetchAndSaveSurahAyahs($num);
            if ($success) {
                $this->info("✓ Sura ya {$num} imeingizwa kikamilifu.");
                return self::SUCCESS;
            } else {
                $this->error("✕ Imeshindikana kupakua Sura ya {$num}.");
                return self::FAILURE;
            }
        }

        if ($allOption) {
            $this->output->progressStart(114);
            $successCount = 0;

            for ($i = 1; $i <= 114; $i++) {
                if ($service->fetchAndSaveSurahAyahs($i)) {
                    $successCount++;
                }
                $this->output->progressAdvance();
                usleep(200000); // 0.2s throttle
            }

            $this->output->progressFinish();
            $this->info("✓ Zimekamilika Sura {$successCount} kati ya 114.");
            return self::SUCCESS;
        }

        $this->warn('Tafadhali taja --surah=<namba> au --all kupakua Sura zote.');
        return self::SUCCESS;
    }
}
