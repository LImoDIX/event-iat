<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SetTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-webhook {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Telegram bot webhook URL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $webhookUrl = $this->argument('url');
        $botToken = env('TELEGRAM_BOT_TOKEN');

        if (!$botToken) {
            $this->error('TELEGRAM_BOT_TOKEN not found in .env file');
            return 1;
        }

        $this->info("Setting webhook to: {$webhookUrl}");

        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/setWebhook", [
                'url' => $webhookUrl
            ]);

            $result = $response->json();

            if ($result['ok']) {
                $this->info('✅ Webhook set successfully!');
                $this->info('Description: ' . ($result['description'] ?? 'N/A'));
            } else {
                $this->error('❌ Failed to set webhook');
                $this->error('Error: ' . ($result['description'] ?? 'Unknown error'));
            }

            return $result['ok'] ? 0 : 1;
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
            return 1;
        }
    }
}
