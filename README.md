# Venalia — Instrucciones de instalación con Docker

> **Este proyecto está pensado principalmente para Windows con WSL2.**
> Todos los comandos de esta guía se ejecutan desde la **terminal de WSL2** (o Git Bash), no desde CMD ni PowerShell.

---

## Requisitos previos

### Windows (recomendado)

1. **WSL2** instalado y configurado — [Guía oficial de Microsoft](https://learn.microsoft.com/es-es/windows/wsl/install)
   - Abre PowerShell como administrador y ejecuta:
     ```powershell
     wsl --install
     ```
   - Reinicia el equipo. Por defecto instala Ubuntu.

2. **Docker Desktop** — [Descargar](https://www.docker.com/products/docker-desktop/)
   - Durante la instalación, activa la opción **"Use the WSL 2 based engine"**
   - En Docker Desktop > Settings > Resources > WSL Integration: activa tu distro de Ubuntu

3. **Git** — instálalo dentro de WSL2:
   ```bash
   sudo apt update && sudo apt install git -y
   ```

4. **Node.js** — instálalo dentro de WSL2 (para compilar assets con Vite):
   ```bash
   curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
   sudo apt install -y nodejs
   ```

> A partir de aquí, **todos los comandos se ejecutan en la terminal de WSL2** (busca "Ubuntu" en el menú inicio).

### macOS / Linux

- Docker Desktop instalado y en ejecución
- Git y Node.js instalados

---

## 1. Clonar el repositorio

Desde la terminal de WSL2 (en Windows) o terminal normal (macOS/Linux):

```bash
git clone https://github.com/Makiflay86/proyecto.git
cd proyecto
```

> **Windows:** clona el proyecto dentro del sistema de archivos de WSL2, no en `/mnt/c/...`.
> La ruta recomendada es tu home de Ubuntu: `~/proyectos/proyecto`
> Clonar en `C:\Users\...` y acceder desde WSL2 es mucho más lento.

---

## 2. Instalar dependencias de PHP

Genera la carpeta `vendor` sin necesidad de tener PHP instalado localmente:

```bash
docker run --rm \
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

> **Aviso:** `db:seed` añade categorías y productos de prueba. **Nunca uses `migrate:fresh --seed`** en un entorno con datos reales porque borra toda la base de datos.

---

## 6. Instalar dependencias de Node y compilar assets

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

Una vez levantado Docker, accede desde el navegador de Windows con normalidad:

| Servicio                    | URL                    |
|-----------------------------|------------------------|
| Aplicación web              | http://localhost       |
| phpMyAdmin                  | http://localhost:8080  |
| Mailpit (correos de prueba) | http://localhost:8025  |

**phpMyAdmin**
- Usuario: el definido en `.env` (`DB_USERNAME`)
- Contraseña: la definida en `.env` (`DB_PASSWORD`)

**Mailpit** — captura todos los emails que envía la app para poder revisarlos sin enviarlos de verdad. Asegúrate de tener en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

---

## Backup de la base de datos

El proyecto incluye un comando artisan para generar backups completos de la BD. Los archivos se guardan en `storage/backups/` con el nombre `backup_YYYYMMDD_HHMMSS.sql`.

```bash
./vendor/bin/sail artisan backup:database
```

**Recomendación:** haz un backup antes de cualquier cambio importante en la BD o antes de cambiar de equipo.

El comando guarda automáticamente solo los **últimos 5 backups** — cuando se crea el sexto, elimina el más antiguo.

El backup **no se ejecuta automáticamente** en desarrollo, ejecútalo manualmente cuando lo necesites.

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

# Limpiar caché (útil si algo no se actualiza visualmente)
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear

# Backup de la BD
./vendor/bin/sail artisan backup:database
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

---

## Problemas frecuentes en Windows

**Docker no arranca o da error de WSL2**
- Abre Docker Desktop y comprueba que el icono de la barra de tareas está verde.
- Ejecuta `wsl --update` en PowerShell para actualizar WSL2.

**`./vendor/bin/sail` da "Permission denied"**
- Ejecuta `chmod +x vendor/bin/sail` desde la terminal de WSL2.

**Los cambios en archivos no se reflejan / Vite no detecta cambios**
- Asegúrate de que el proyecto está clonado dentro de WSL2 (`~/...`) y no en `/mnt/c/...`. El rendimiento y la detección de cambios de archivos son muy deficientes desde la ruta de Windows.

**Puerto 80 ocupado**
- Algún programa (IIS, Skype, otro Docker) puede estar usando el puerto 80. Detén ese servicio o cambia el puerto en `docker-compose.yml`.
