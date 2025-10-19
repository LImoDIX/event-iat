<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Настройка конфигурации почты по умолчанию
        $this->configureDefaultMailSettings();
    }

    /**
     * Configure default mail settings.
     */
    protected function configureDefaultMailSettings(): void
    {
        // Если MAIL_MAILER не установлен, используем 'log' как значение по умолчанию
        if (empty(Config::get('mail.default')) || Config::get('mail.default') === 'log') {
            Config::set('mail.default', 'smtp');
        }

        // Если хост не установлен, используем 'localhost' как значение по умолчанию
        if (empty(Config::get('mail.mailers.smtp.host')) || Config::get('mail.mailers.smtp.host') === '127.0.0.1') {
            Config::set('mail.mailers.smtp.host', 'localhost');
        }

        // Если порт не установлен, используем 1025 как значение по умолчанию (MailHog)
        if (empty(Config::get('mail.mailers.smtp.port')) || Config::get('mail.mailers.smtp.port') == 2525) {
            Config::set('mail.mailers.smtp.port', 1025);
        }

        // Если MAIL_FROM_ADDRESS не установлен, используем 'noreply@example.com' как значение по умолчанию
        if (empty(Config::get('mail.from.address')) || Config::get('mail.from.address') === 'hello@example.com') {
            Config::set('mail.from.address', 'noreply@event-attendance.local');
        }

        // Если MAIL_FROM_NAME не установлен, используем имя приложения как значение по умолчанию
        if (empty(Config::get('mail.from.name')) || Config::get('mail.from.name') === '${APP_NAME}') {
            Config::set('mail.from.name', Config::get('app.name', 'Event Attendance'));
        }
    }
}
