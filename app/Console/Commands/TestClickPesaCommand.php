<?php

namespace App\Console\Commands;

use App\Services\Payments\ClickPesaService;
use Illuminate\Console\Command;

class TestClickPesaCommand extends Command
{
    protected $signature = 'clickpesa:test';

    protected $description = 'Jaribu mawasiliano na API ya ClickPesa na thibitisha funguo zako za Client ID na API Key';

    public function handle(ClickPesaService $clickPesa): int
    {
        $this->info('==================================================');
        $this->info('  UKAGUZI WA MAWASILIANO YA CLICKPESA (TANZANIA)  ');
        $this->info('==================================================');

        $clientId = config('services.clickpesa.client_id');
        $apiKey = config('services.clickpesa.api_key');
        $mode = config('services.clickpesa.mode', 'live');

        $this->line("• Mazingira (Mode): <comment>{$mode}</comment>");
        $this->line('• Client ID: ' . ($clientId ? "<info>" . substr($clientId, 0, 8) . '...</info>' : '<error>HAIJAWEKWA</error>'));
        $this->line('• API Key: ' . ($apiKey ? '<info>IMEWEKWA</info>' : '<error>HAIJAWEKWA</error>'));

        if (empty($clientId) || empty($apiKey)) {
            $this->newLine();
            $this->error('Tafadhali weka CLICKPESA_CLIENT_ID na CLICKPESA_API_KEY kwenye faili lako la .env kwanza.');
            $this->line('Mfano:');
            $this->line('  CLICKPESA_CLIENT_ID=namba_yako_ya_client_id');
            $this->line('  CLICKPESA_API_KEY=funguo_yako_ya_api_key');

            return self::FAILURE;
        }

        $this->newLine();
        $this->comment('Inawasiliana na seva ya ClickPesa (https://api.clickpesa.com)...');

        $token = $clickPesa->getAccessToken();

        if ($token) {
            $this->newLine();
            $this->info('✓ HONGERA! Mawasiliano na ClickPesa yamefaulu kwa 100%.');
            $this->line('• Token ya JWT imezalishwa na kuthibitishwa kwa usalama.');
            $this->newLine();

            $webhookUrl = url('/malipo/webhook/clickpesa');
            $this->comment('Taarifa za Webhook kwenye ClickPesa Portal:');
            $this->line("• Weka Webhook URL hii kwenye Dashboard yako ya ClickPesa: <info>{$webhookUrl}</info>");
            $this->line('• Matukio ya kuwezesha: <comment>PAYMENT RECEIVED, PAYMENT FAILED</comment>');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->error('✕ Imeshindwa kuthibitisha funguo za ClickPesa.');
        if ($err = $clickPesa->getLastAuthError()) {
            $this->warn("Sababu kutoka ClickPesa: {$err}");
        }
        $this->line('Tafadhali kagua kama Client ID au API Key uliyoweka kwenye .env ni sahihi.');

        return self::FAILURE;
    }
}
