<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncAudioToBucket extends Command
{
    protected $signature = 'hadith:audio-sync-bucket
        {--key= : S3 / Cloudflare Access Key ID}
        {--secret= : S3 / Cloudflare Secret Access Key}
        {--endpoint= : S3 Endpoint URL (e.g. https://xxx.r2.cloudflarestorage.com)}
        {--bucket= : Jina la bucket}
        {--region= : Region ya S3 (inakuwa auto kwa Cloudflare R2)}
        {--limit=0 : Idadi ya juu ya faili kupakia (0 = zote)}
        {--force : Pakia upya hata kama faili tayari lipo kwenye bucket}
        {--dry-run : Hesabu tu faili bila kutuma chochote}';

    protected $description = 'Pakia faili zote za sauti (audio_cache) kutoka storage ya ndani kuelekea Laravel Cloud Bucket';

    public function handle(): int
    {
        $key = $this->option('key') ?: (config('filesystems.disks.s3.key') ?: config('filesystems.disks.hadith-audio-3.key'));
        $secret = $this->option('secret') ?: (config('filesystems.disks.s3.secret') ?: config('filesystems.disks.hadith-audio-3.secret'));
        $endpoint = $this->option('endpoint') ?: (config('filesystems.disks.s3.endpoint') ?: config('filesystems.disks.hadith-audio-3.endpoint'));
        $bucket = $this->option('bucket') ?: (config('filesystems.disks.s3.bucket') ?: config('filesystems.disks.hadith-audio-3.bucket'));
        $region = $this->option('region') ?: (config('filesystems.disks.s3.region') ?: config('filesystems.disks.hadith-audio-3.region'));

        if (blank($endpoint) && ! blank(config('filesystems.disks.s3.endpoint'))) {
            $endpoint = config('filesystems.disks.s3.endpoint');
        }

        // Cloudflare R2 inahitaji region iwe 'auto'
        if (empty($region) || (is_string($endpoint) && str_contains($endpoint, 'r2.cloudflarestorage.com'))) {
            $region = 'auto';
        }

        $localDisk = Storage::disk('private');
        $files = $localDisk->allFiles('audio_cache');

        if (empty($files)) {
            $this->warn('Hakuna faili za sauti zilizopatikana katika storage/app/private/audio_cache.');
            return self::SUCCESS;
        }

        $limit = max(0, (int) $this->option('limit'));
        if ($limit > 0) {
            $files = array_slice($files, 0, $limit);
        }

        $this->info(sprintf('Jumla ya faili za sauti za ndani (audio_cache): %d', count($files)));

        $hasCredentials = ! (blank($key) || blank($secret) || blank($bucket));

        if ($this->option('dry-run')) {
            if (! $hasCredentials) {
                $this->comment('Hali ya majaribio (Dry Run): Faili zote zipo tayari kusawazishwa pindi vitambulisho vya S3/R2 vitakapowekwa kwenye .env.');
                return self::SUCCESS;
            }

            // Kagua muunganisho wa Cloud halisi
            config([
                'filesystems.disks.cloud_sync' => [
                    'driver' => 's3',
                    'key' => $key,
                    'secret' => $secret,
                    'region' => $region,
                    'bucket' => $bucket,
                    'endpoint' => $endpoint,
                    'use_path_style_endpoint' => false,
                    'throw' => false,
                    'report' => false,
                ],
            ]);

            try {
                $cloudDisk = Storage::disk('cloud_sync');
                $cloudFiles = $cloudDisk->allFiles('audio_cache');
                $cloudCount = count($cloudFiles);
                $missingFromCloud = count(array_diff($files, $cloudFiles));

                $this->info('✓ Muunganisho wa Cloud: Imefanikiwa (Connected)');
                $this->line("  Bucket: {$bucket} ({$endpoint})");
                $this->line("  Faili za ndani (Local): ".count($files));
                $this->line("  Faili zilizopo Cloud: {$cloudCount}");
                if ($missingFromCloud === 0) {
                    $this->info('  ✓ Hali ya Usawazishaji: Faili zote zipo Cloud tayari (100% In Sync). Hakuna faili inayokosekana!');
                } else {
                    $this->comment("  ↷ Faili zinazosubiri kupakiwa (Pending Upload): {$missingFromCloud}");
                }

                return self::SUCCESS;
            } catch (\Throwable $e) {
                $this->comment('Hali ya majaribio (Dry Run): Vitambulisho vimesanidiwa. Muunganisho wa moja kwa moja haukupatikana (mtandao haupatikani au umefungwa).');
                return self::SUCCESS;
            }
        }

        if (! $hasCredentials) {
            $this->error('Taarifa za kuunganisha Bucket hazijakamilika.');
            $this->newLine();
            $this->line('Tafadhali jaza options kwenye amri hii au ziweke kwenye .env:');
            $this->line('  AWS_ACCESS_KEY_ID=xxx');
            $this->line('  AWS_SECRET_ACCESS_KEY=yyy');
            $this->line('  AWS_BUCKET=hadith-audio-3');
            $this->line('  AWS_ENDPOINT=https://xxx.r2.cloudflarestorage.com');
            $this->newLine();
            $this->line('Mfano wa kutumia amri:');
            $this->line('  php artisan hadith:audio-sync-bucket');

            return self::FAILURE;
        }

        // Sanidi disk ya S3
        config([
            'filesystems.disks.cloud_sync' => [
                'driver' => 's3',
                'key' => $key,
                'secret' => $secret,
                'region' => $region,
                'bucket' => $bucket,
                'endpoint' => $endpoint,
                'use_path_style_endpoint' => false,
                'throw' => false,
                'report' => false,
            ],
        ]);

        try {
            $cloudDisk = Storage::disk('cloud_sync');
        } catch (\Throwable $e) {
            $this->error('Imeshindikana kuandaa muunganisho wa Bucket: '.$e->getMessage());
            return self::FAILURE;
        }

        $this->line("Inapakia kwenye bucket: <info>{$bucket}</info> ({$endpoint})...");

        $uploaded = 0;
        $skipped = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $file) {
            try {
                if (! $this->option('force') && $cloudDisk->exists($file)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                $stream = $localDisk->readStream($file);
                if ($stream === false) {
                    $failed++;
                    $bar->advance();
                    continue;
                }

                $cloudDisk->writeStream($file, $stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }

                $uploaded++;
            } catch (\Throwable $e) {
                $failed++;
                $this->newLine();
                $this->error("Kosa kwenye {$file}: ".$e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Kazi imekamilika:");
        $this->line("  ✓ Zilizopakiwa (Uploaded): {$uploaded}");
        $this->line("  ↷ Zilizorukwa (Skipped - tayari zipo): {$skipped}");
        if ($failed > 0) {
            $this->warn("  ✗ Zilizofeli (Failed): {$failed}");
        }

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }
}
