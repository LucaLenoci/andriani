<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            padding: 40px;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        .logo {
            margin-bottom: 20px;
        }
        .btn {
            background-color: #2c3e50;
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img class="logo" src="http://testandriani.promomedia.online/adminlte/dist/assets/img/logo-andriani.png" alt="Logo Azienda" height="60">


        <h2>Ciao {{ $user->name ?? 'Utente' }},</h2>

        <p>Hai richiesto il reset della tua password. Clicca il pulsante qui sotto per continuare:</p>

        <a href="{{ $url }}" class="btn">Resetta la password</a>

        <p>Se non hai richiesto questa operazione, puoi ignorare questa email.</p>

        <div class="footer">
            &copy; {{ date('Y') }} Andriani SpA. Tutti i diritti riservati.
        </div>
    </div>
</body>
</html>
