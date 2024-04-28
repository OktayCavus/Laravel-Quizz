<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Şifre Sıfırlama Talebi Alındı</title>
    <style>
        /* Inline styles for simplicity, consider using CSS classes for larger templates */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f1f1f1;
        }

        .message {
            padding: 20px;
            background-color: #ffffff;
        }

        .message p {
            margin-bottom: 10px;
        }

        .code-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px auto;
            max-width: 200px;
            text-align: center;
        }

        .code-text {
            font-weight: bold;
            font-size: 18px;
        }

        .thanks {
            text-align: center;
            margin-top: 20px;
            font-style: italic;
        }

        .app-name {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="message">
            <h1>Şifre sıfırlama talebinizi aldık</h1>
            <p>{{ $mailData }} için şifre sıfırlama kodu:</p>
            <div class="code-box">
                <p class="code-text">{{ $code }}</p>
            </div>
        </div>
        <div class="thanks">
            Teşekkürler,<br>
            <span class="app-name">{{ config('app.name') }}</span>
        </div>
    </div>
</body>

</html>