<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetTelegramWebhookInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:webhook-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Telegram bot webhook information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');

        if (!$botToken) {
            $this->error('TELEGRAM_BOT_TOKEN not found in .env file');
            return 1;
        }

        try {
            $response = Http::get("https://api.telegram.org/bot{$botToken}/getWebhookInfo");
            $result = $response->json();

            if ($result['ok']) {
                $info = $result['result'];
                
                $this->info('📋 Webhook Information:');
                $this->info('URL: ' . ($info['url'] ?: 'Not set'));
                $this->info('Has custom certificate: ' . ($info['has_custom_certificate'] ? 'Yes' : 'No'));
                $this->info('Pending update count: ' . $info['pending_update_count']);
                
                if (isset($info['last_error_date'])) {
                    $this->warn('Last error date: ' . date('Y-m-d H:i:s', $info['last_error_date']));
                    $this->warn('Last error message: ' . ($info['last_error_message'] ?? 'N/A'));
                }
                
                if (isset($info['max_connections'])) {
                    $this->info('Max connections: ' . $info['max_connections']);
                }
            } else {
                $this->error('❌ Failed to get webhook info');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
            return 1;
        }
    }
}
