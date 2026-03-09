# Instrucciones de instalación con Docker

## Requisitos previos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y en ejecución
- Git instalado
- Node.js instalado en el **host** (para compilar assets con Vite)

---

## 1. Clonar el repositorio

```bash
git clone https://github.com/Makiflay86/proyecto.git
cd proyecto
```

---

## 2. Instalar dependencias de PHP

Genera la carpeta `vendor` sin necesidad de tener PHP instalado localmente:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

---

## 3. Configurar el entorno

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

## 5. Generar clave, migrar y enlazar storage

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan storage:link
```

Si quieres poblar la base de datos con datos de prueba:

```bash
./vendor/bin/sail artisan db:seed
```

---

## 6. Instalar dependencias de Node y compilar assets

> ⚠️ Vite tiene un bug con rollup dentro del contenedor ARM. Ejecuta estos comandos en el **host** (fuera de Sail):

```bash
npm install
npm run build
```

Para desarrollo con hot-reload:

```bash
npm run dev
```

---

## Servicios disponibles

Una vez levantado Docker:

| Servicio | URL |
|---|---|
| Aplicación web | http://localhost |
| phpMyAdmin | http://localhost:8080 |
| Mailpit (correos de prueba) | http://localhost:8025 |

**phpMyAdmin**
- Usuario: el definido en `.env` (`DB_USERNAME`)
- Contraseña: la definida en `.env` (`DB_PASSWORD`)

**Mailpit** — para que los mails se envíen aquí, asegúrate de tener en `.env`:

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
./vendor/bin/sail artisan view:clear

```

---

## Mensaje diario automático

La app genera un mensaje motivacional diario. Para ejecutarlo manualmente:

```bash
./vendor/bin/sail artisan app:generate-daily-message
```

En producción, añade un cron para que se ejecute automáticamente:

```bash
* * * * * cd /ruta-del-proyecto && php artisan schedule:run >> /dev/null 2>&1
```
