<p align="center">
  <img src="public/images/logo.svg" alt="Venalia" height="80">
</p>

# Venalia — Plataforma de compra y venta

Venalia es una aplicación web de compra-venta donde cualquier usuario registrado puede publicar productos, contactar con vendedores mediante chat en tiempo real y guardar sus favoritos.

---

## Funcionalidades principales

### Tienda pública
- Catálogo de productos con filtrado por categoría en modo **drill-down** reactivo (Livewire): seleccionas una categoría raíz y aparecen sus subcategorías, luego las de estas, y así sucesivamente
- Búsqueda por nombre/descripción desde la barra de navegación
- Ordenación por precio (asc/desc) o por más recientes, reactiva sin recargar la página
- Galería de imágenes con lightbox y navegación por teclado en el detalle del producto
- Nombre del autor visible en cada tarjeta y en el detalle, **clickable** — lleva al perfil público del vendedor

### Perfiles públicos de usuario
- Cualquier visitante puede ver el perfil de un vendedor en `/usuarios/{user}`
- Muestra foto/inicial, nombre, fecha de registro, número de productos en venta y la grid de productos
- Si es tu propio perfil, aparece un botón "Editar perfil"

### Sistema de ventas (marcar como vendido)
- El dueño de un producto puede marcarlo como **vendido** desde la página del producto
- Los productos vendidos desaparecen de la tienda pero su página sigue accesible con un badge rojo "Vendido"
- En "Mis productos" los vendidos aparecen con badge rojo y la imagen a media opacidad
- El dueño puede volver a poner el producto en venta en cualquier momento
- La venta es siempre en persona; la plataforma solo gestiona la comunicación

### Sistema de likes (favoritos)
- Botón de corazón en cada producto (tienda y detalle)
- Los productos marcados se guardan en "Mis favoritos", accesible desde el menú de usuario
- Si el usuario no está autenticado, el botón redirige al login

### Chat entre comprador y vendedor
- Desde el detalle de un producto, el botón "Contactar con el vendedor" abre un chat privado
- La cabecera del chat muestra la imagen y nombre del producto, **clickable** al detalle del producto, y el nombre real del vendedor
- Las conversaciones se identifican por producto + comprador, de modo que el vendedor puede tener hilos separados con cada interesado
- Actualizacion automatica cada 3 segundos (Livewire polling)
- Auto-scroll al mensaje mas reciente
- "Mis mensajes" muestra todos los hilos activos con indicador de mensajes no leidos
- Badge en el menú de usuario con el número de conversaciones con mensajes sin leer

### Publicar productos (todos los usuarios)
- Cualquier usuario registrado puede publicar productos desde el menu de usuario ("Publicar producto") o desde su perfil ("Anadir producto")
- Formulario con subida de multiples imagenes, categoria, precio, descripcion y estado
- Los productos publicados aparecen en la tienda publica

### Perfil de usuario
- Pagina `/mi-perfil` con edicion de nombre, email y contrasena
- Seccion "Mis productos" con las publicaciones propias, incluyendo badge de vendido en los ya cerrados

### Panel de administracion
- Solo accesible para usuarios con `is_admin = true`
- CRUD completo de productos y categorias (con jerarquia padre-hijo y filtro drill-down)
- Vista de perfil de cualquier usuario (`/usuarios/{user}`) con sus productos
- Los hilos de chat de todos los usuarios son visibles para el administrador

---

## Instrucciones de instalacion con Docker

> **Este proyecto esta pensado principalmente para Windows con WSL2.**
> Todos los comandos de esta guia se ejecutan desde la **terminal de WSL2** (o Git Bash), no desde CMD ni PowerShell.

---

## Requisitos previos

### Windows (recomendado)

1. **WSL2** instalado y configurado — [Guia oficial de Microsoft](https://learn.microsoft.com/es-es/windows/wsl/install)
   - Abre PowerShell como administrador y ejecuta:
     ```powershell
     wsl --install
     ```
   - Reinicia el equipo. Por defecto instala Ubuntu.

2. **Docker Desktop** — [Descargar](https://www.docker.com/products/docker-desktop/)
   - Durante la instalacion, activa la opcion **"Use the WSL 2 based engine"**
   - En Docker Desktop > Settings > Resources > WSL Integration: activa tu distro de Ubuntu

3. **Git** — instalalo dentro de WSL2:
   ```bash
   sudo apt update && sudo apt install git -y
   ```

4. **Node.js** — instalalo dentro de WSL2 (para compilar assets con Vite):
   ```bash
   curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
   sudo apt install -y nodejs
   ```

> A partir de aqui, **todos los comandos se ejecutan en la terminal de WSL2** (busca "Ubuntu" en el menu inicio).

### macOS / Linux

- Docker Desktop instalado y en ejecucion
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
> Clonar en `C:\Users\...` y acceder desde WSL2 es mucho mas lento.

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

Edita `.env` si necesitas cambiar algun valor (base de datos, mail, zona horaria, etc.).

Asegurate de tener la zona horaria correcta:

```env
APP_TIMEZONE=Europe/Madrid
```

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

> **Aviso:** `db:seed` anade categorias y productos de prueba. **Nunca uses `migrate:fresh --seed`** en un entorno con datos reales porque borra toda la base de datos.

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

> El proyecto tiene varios entry points de Vite: `app.js` (global), `dashboard.js` (panel de gestion) y `auth.js` (formularios de autenticacion).

---

## Servicios disponibles

Una vez levantado Docker, accede desde el navegador:

| Servicio                    | URL                    |
|-----------------------------|------------------------|
| Aplicacion web              | http://localhost       |
| phpMyAdmin                  | http://localhost:8080  |
| Mailpit (correos de prueba) | http://localhost:8025  |

**phpMyAdmin**
- Usuario: el definido en `.env` (`DB_USERNAME`)
- Contrasena: la definida en `.env` (`DB_PASSWORD`)

**Mailpit** — captura todos los emails que envia la app para poder revisarlos sin enviarlos de verdad. Asegurate de tener en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

---

## Roles y administracion

El sistema distingue dos tipos de usuario: **usuarios registrados** y **administradores**.

### Campo `is_admin`

La tabla `users` tiene una columna booleana `is_admin` (por defecto `false`). Solo los usuarios con `is_admin = true` pueden acceder al panel de gestion.

Los usuarios registrados desde el formulario de registro **nunca** obtienen acceso de administrador automaticamente.

### Dar permisos de administrador a un usuario

Desde Tinker:

```bash
./vendor/bin/sail artisan tinker
```

```php
User::where('email', 'correo@ejemplo.com')->update(['is_admin' => true]);
```

O directamente en phpMyAdmin: pon `is_admin = 1` en el registro del usuario.

### Que ve cada usuario

| Seccion                              | Visitante | Usuario normal | Administrador |
|--------------------------------------|:---------:|:--------------:|:-------------:|
| Tienda publica                       | Si        | Si             | Si            |
| Busqueda y filtros drill-down        | Si        | Si             | Si            |
| Ver perfil publico de un usuario     | Si        | Si             | Si            |
| Dar like a productos                 | No        | Si             | Si            |
| Mis favoritos                        | No        | Si             | Si            |
| Chat con el vendedor                 | No        | Si             | Si            |
| Mis mensajes (badge no leidos)       | No        | Si             | Si (todos)    |
| Publicar productos                   | No        | Si             | Si            |
| Marcar producto como vendido         | No        | Si (propio)    | Si            |
| Mi perfil (editar datos)             | No        | Si             | Si            |
| Panel de gestion                     | No        | No             | Si            |
| CRUD productos y categorias          | No        | No             | Si            |

---

## Rutas principales

| Metodo | Ruta                               | Descripcion                                  |
|--------|------------------------------------|----------------------------------------------|
| GET    | `/`                                | Catalogo de productos                        |
| GET    | `/producto/{product}`              | Detalle del producto                         |
| PATCH  | `/producto/{product}/vendido`      | Marcar producto como vendido (dueño)         |
| PATCH  | `/producto/{product}/reactivar`    | Volver a poner en venta (dueño)              |
| GET    | `/mis-favoritos`                   | Productos marcados con like                  |
| GET    | `/publicar`                        | Formulario para publicar un nuevo producto   |
| POST   | `/publicar`                        | Guardar nuevo producto                       |
| GET    | `/mis-mensajes`                    | Lista de conversaciones                      |
| GET    | `/chat/{product}`                  | Chat del comprador con el vendedor           |
| GET    | `/chat/{product}/{user}`           | Hilo especifico (solo admin)                 |
| GET    | `/mi-perfil`                       | Perfil propio (editar datos + mis productos) |
| GET    | `/usuarios/{user}`                 | Perfil publico de cualquier usuario          |
| GET    | `/dashboard`                       | Panel de gestion (solo admin)                |

---

## Backup de la base de datos

El proyecto incluye un comando artisan para generar backups completos de la BD. Los archivos se guardan en `storage/backups/` con el nombre `backup_YYYYMMDD_HHMMSS.sql`.

```bash
./vendor/bin/sail artisan backup:database
```

**Recomendacion:** haz un backup antes de cualquier cambio importante en la BD o antes de cambiar de equipo.

El comando guarda automaticamente solo los **ultimos 5 backups** — cuando se crea el sexto, elimina el mas antiguo.

---

## Comandos utiles

```bash
# Ver logs en tiempo real
./vendor/bin/sail logs -f

# Abrir una terminal dentro del contenedor
./vendor/bin/sail shell

# Ejecutar comandos artisan
./vendor/bin/sail artisan <comando>

# Ejecutar tests
./vendor/bin/sail test

# Limpiar cache (util si algo no se actualiza visualmente)
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear

# Backup de la BD
./vendor/bin/sail artisan backup:database
```

---

## Mensaje diario automatico

La app genera un mensaje motivacional diario. Para ejecutarlo manualmente:

```bash
./vendor/bin/sail artisan app:generate-daily-message
```

En produccion, anade un cron para que se ejecute automaticamente:

```bash
* * * * * cd /ruta-del-proyecto && php artisan schedule:run >> /dev/null 2>&1
```

---

## Problemas frecuentes en Windows

**Docker no arranca o da error de WSL2**
- Abre Docker Desktop y comprueba que el icono de la barra de tareas esta verde.
- Ejecuta `wsl --update` en PowerShell para actualizar WSL2.

**`./vendor/bin/sail` da "Permission denied"**
- Ejecuta `chmod +x vendor/bin/sail` desde la terminal de WSL2.

**Los cambios en archivos no se reflejan / Vite no detecta cambios**
- Asegurate de que el proyecto esta clonado dentro de WSL2 (`~/...`) y no en `/mnt/c/...`. El rendimiento y la deteccion de cambios de archivos son muy deficientes desde la ruta de Windows.

**Puerto 80 ocupado**
- Algun programa (IIS, Skype, otro Docker) puede estar usando el puerto 80. Detena ese servicio o cambia el puerto en `docker-compose.yml`.
