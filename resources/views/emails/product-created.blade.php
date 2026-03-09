<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body        { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container  { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .header     { background: #4f46e5; padding: 30px; text-align: center; }
        .header h1  { color: #fff; margin: 0; font-size: 22px; }
        .body       { padding: 30px; color: #333; }
        .body p     { line-height: 1.6; }
        .product    { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .product h2 { margin: 0 0 10px; color: #111; font-size: 18px; }
        .product p  { margin: 4px 0; font-size: 14px; color: #555; }
        .price      { font-size: 20px; font-weight: bold; color: #4f46e5; margin-top: 10px !important; }
        .footer     { background: #f9fafb; padding: 20px 30px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">

        <div class="header">
            <h1>Producto creado correctamente</h1>
        </div>

        <div class="body">
            <p>Hola, <strong>{{ $user->name }}</strong>.</p>
            <p>Tu producto ha sido añadido con éxito. Aquí tienes un resumen:</p>

            <div class="product">
                <h2>{{ $product->nombre }}</h2>
                <p>{{ $product->descripcion }}</p>
                <p><strong>Categoría:</strong> {{ $product->category?->name }}</p>
                <p><strong>Estado:</strong> {{ $product->estado }}</p>
                <p class="price">${{ number_format($product->precio, 2) }}</p>
            </div>

            <p>Si no has realizado esta acción, contacta con el soporte.</p>
        </div>

        <div class="footer">
            Este email fue generado automáticamente, por favor no respondas.
        </div>

    </div>
</body>
</html>
