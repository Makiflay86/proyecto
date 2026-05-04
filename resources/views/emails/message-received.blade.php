<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo mensaje recibido</title>
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
            border-bottom: 2px solid #3b82f6;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #1e3a8a;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .message-box {
            background-color: #f9fafb;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            font-style: italic;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #666;
            text-align: center;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: #ffffff !important;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hola, {{ $recipient->name }}</h1>
        </div>
        <div class="content">
            <p>Has recibido un nuevo mensaje de <strong>{{ $sender->name }}</strong> sobre el producto <strong>{{ $msg->product->nombre }}</strong>.</p>
            
            <div class="message-box">
                "{{ $msg->body }}"
            </div>

            <p>Puedes responder a este mensaje directamente desde la plataforma:</p>
            
            <a href="{{ route('chat.thread', [$msg->product_id, $msg->thread_user_id]) }}" class="button">Responder al mensaje</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Venalia. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
