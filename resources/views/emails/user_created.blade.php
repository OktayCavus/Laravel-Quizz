<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Created</title>
</head>

<body>
    <p>Merhaba {{ $user->name }},</p>
    <p>Hesabın başarıyla oluşturuldu</p>
    <p>Teşekkürler !</p>
    <a href="http://127.0.0.1:8000/api/email/verify/{{ $user->id }}"> Hesabını Aktifleştirmek İçin Buraya tıkla </a>
</body>

</html>