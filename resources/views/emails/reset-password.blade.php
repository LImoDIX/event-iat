<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сброс пароля</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0e0e10;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #1f1f23;
            border-radius: 10px;
            border: 1px solid #3f3f46;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #3f3f46;
        }
        .header h1 {
            color: #ec4899;
            margin: 0;
        }
        .content {
            padding: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(90deg, #8b5cf6, #ec4899);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #3f3f46;
            font-size: 12px;
            color: #a1a1aa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Сброс пароля</h1>
        </div>
        <div class="content">
            <p>Здравствуйте!</p>
            <p>Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.</p>
            <p style="text-align: center;">
                <a href="{{ $url }}" class="button">Сбросить пароль</a>
            </p>
            <p>Эта ссылка для сброса пароля будет действительна в течение 60 минут.</p>
            <p>Если вы не запрашивали сброс пароля, никаких дополнительных действий не требуется.</p>
            <p>С уважением,<br>Команда {{ config('app.name') }}</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. Все права защищены.</p>
        </div>
    </div>
</body>
</html>
