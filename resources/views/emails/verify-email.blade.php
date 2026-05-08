<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu correo — Venalia</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            border-bottom: 2px solid #d97706;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #d97706;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background-color: #d97706;
            color: #ffffff !important;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer {
            font-size: 12px;
            color: #666;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Venalia</h1>
        </div>
        <div class="content">
            <p>¡Hola, <strong>{{ $name }}</strong>!</p>
            <p>Gracias por registrarte. Confirma tu dirección de correo electrónico haciendo clic en el botón:</p>
            <a href="{{ $url }}" class="button">Verificar correo electrónico</a>
            <p style="margin-top: 20px; font-size: 14px; color: #666;">
                Este enlace expirará en 60 minutos.<br>
                Si no creaste una cuenta en Venalia, puedes ignorar este mensaje.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Venalia. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
