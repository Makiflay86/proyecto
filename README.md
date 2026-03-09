# Instrucciones de instalación con Docker

## Requisitos previos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y en ejecución
- Git instalado

---

## 1. Clonar el repositorio

```bash
git clone https://github.com/Makiflay86/proyecto.git

cd proyecto
```

---

## 2. Instalar dependencias de PHP

Ejecuta este comando para crear la carpeta `vendor` (solo la primera vez):

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

---

## 3. Configurar el archivo de entorno

```bash
cp .env.example .env
```

Edita `.env` si necesitas cambiar algún valor (base de datos, mail, etc.).

---

## 4. Levantar Docker

```bash
./vendor/bin/sail up -d
```

Para parar los contenedores:

```bash
./vendor/bin/sail down
```

---

## 5. Generar clave y migrar la base de datos

```bash
./vendor/bin/sail artisan key:generate

./vendor/bin/sail artisan migrate
```

Si quieres poblar la base de datos con datos de prueba:

```bash
./vendor/bin/sail artisan db:seed
```

---

## Servicios disponibles

Una vez levantado Docker, tienes acceso a los siguientes servicios:

### Aplicación web
- URL: [http://localhost](http://localhost)

### phpMyAdmin
Interfaz gráfica para gestionar la base de datos MySQL.
- URL: [http://localhost:8080](http://localhost:8080)
- Usuario: el definido en `.env` (`DB_USERNAME`)
- Contraseña: la definida en `.env` (`DB_PASSWORD`)

### Mailpit (bandeja de entrada de prueba)
Captura todos los correos enviados por la app en local para que no lleguen a nadie real.
- URL: [http://localhost:8025](http://localhost:8025)

Para que los mails se envíen a Mailpit, asegúrate de tener esto en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

---

## Comandos útiles

```bash
# Ver logs en tiempo real
./vendor/bin/sail logs -f

# Abrir una terminal dentro del contenedor
./vendor/bin/sail shell

# Ejecutar comandos artisan
./vendor/bin/sail artisan <comando>

# Ejecutar tests
./vendor/bin/sail test

# Limpiar caché
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
```

---

## Mensaje diario automático

La app genera un mensaje motivacional diario. Para ejecutarlo manualmente:

```bash
./vendor/bin/sail artisan app:generate-daily-message
```

Para que se ejecute automáticamente cada día, añade esta tarea al scheduler de Laravel (ya configurada en `app/Console/Kernel.php` si la tienes definida). En producción necesitarías un cron que ejecute:

```bash
* * * * * cd /ruta-del-proyecto && php artisan schedule:run >> /dev/null 2>&1
```
