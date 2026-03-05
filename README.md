INSTRUCCIONES PARA INSTALAR EN CASA (O EN OTRO ORDENADOR)

1. Clonar el código de GitHub y entrar en la carpeta desde la terminal.

2. Ejecutar este comando gigante para crear la carpeta "vendor" (solo se hace la primera vez):

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs

3. Crear el archivo de configuración con las contraseñas:
cp .env.example .env

4. Encender los motores de Docker:
./vendor/bin/sail up -d

5. Generar la clave de seguridad y crear las tablas de la base de datos:
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate