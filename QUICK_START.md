# Быстрый старт Telegram бота

## 1. Установите зависимости

\`\`\`bash
composer install
\`\`\`

## 2. Настройте .env

Добавьте в `.env`:

\`\`\`env
TELEGRAM_BOT_TOKEN=8470678650:AAGSNmrA4UvkpOtEKqxeOVT2uanUQqcbFj4
APP_URL=http://localhost:8000
\`\`\`

## 3. Запустите Laravel

\`\`\`bash
php artisan serve
\`\`\`

## 4. Проверьте что работает

Откройте: http://localhost:8000/test

## 5. Запустите Pinggy

\`\`\`bash
ssh -p 443 -R0:localhost:8000 a.pinggy.io
\`\`\`

Скопируйте URL (например: `https://abc123.a.pinggy.link`)

## 6. Обновите APP_URL

В `.env` измените:

\`\`\`env
APP_URL=https://abc123.a.pinggy.link
\`\`\`

Перезапустите Laravel (Ctrl+C и снова `php artisan serve`)

## 7. Установите webhook

\`\`\`bash
php artisan telegram:set-webhook https://abc123.a.pinggy.link/api/telegram/webhook
\`\`\`

## 8. Проверьте

\`\`\`bash
php artisan telegram:webhook-info
\`\`\`

## 9. Тестируйте!

Откройте бота в Telegram и напишите `/start`

## Решение проблемы с Mixed Content

Если вы сталкиваетесь с ошибкой "Mixed Content" при работе через Pinggy, это происходит потому что Laravel генерирует URL с HTTP схемой вместо HTTPS.

Решение уже реализовано в приложении - добавлен специальный middleware `ForceHttps`, который автоматически устанавливает HTTPS схему для всех запросов через Pinggy.

Если вы по-прежнему сталкиваетесь с проблемой:
1. Убедитесь что вы используете URL вида `*.pinggy.link`
2. Перезапустите Laravel сервер и туннель Pinggy

---

**Готово!** Подробные инструкции в файле `PINGGY_SETUP_GUIDE.md`
