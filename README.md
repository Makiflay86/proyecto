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

### Chat entre usuarios (comprador ↔ vendedor)
- Desde el detalle de un producto, el botón "Contactar con el vendedor" abre un chat privado
- **Cualquier usuario registrado puede ser comprador o vendedor** — el chat funciona entre usuarios normales sin requerir rol de administrador
- La cabecera del chat muestra el nombre del producto y el nombre de la contraparte (comprador ve al vendedor, vendedor ve al comprador)
- Las conversaciones se identifican por producto + comprador, de modo que el vendedor puede tener hilos separados con cada interesado
- El vendedor puede ver y responder todos los mensajes recibidos en sus productos desde "Mis mensajes"
- Actualización automática cada 3 segundos (Livewire polling)
- Auto-scroll al mensaje más reciente
- Indicador "leído" en los mensajes enviados por el vendedor

### Notificaciones de mensajes no leídos
- **Punto rojo en el avatar** del navbar — aparece automáticamente en menos de 5 segundos cuando llega un mensaje nuevo, sin necesidad de recargar la página (polling JS)
- **Badge con número** en el menú desplegable junto a "Mis mensajes"
- **Indicador visual** en la lista de conversaciones: texto en negrita + punto azul en los hilos con mensajes sin leer
- Funciona tanto para compradores (cuando el vendedor responde) como para vendedores (cuando un comprador escribe)
- Totalmente adaptado a **dark mode**

### Publicar productos (todos los usuarios)
- Cualquier usuario registrado puede publicar productos desde el menú de usuario ("Publicar producto") o desde su perfil ("Añadir producto")
- Formulario con subida de múltiples imágenes, categoría, precio, descripción y estado
- Los productos publicados aparecen en la tienda pública

### Perfil de usuario
- Página `/mi-perfil` con edición de nombre, email y contraseña
- Sección "Mis productos" con las publicaciones propias, incluyendo badge de vendido en los ya cerrados

### Panel de administración
- Solo accesible para usuarios con `is_admin = true`
- CRUD completo de productos y categorías (con jerarquía padre-hijo y filtro drill-down)
- Vista de perfil de cualquier usuario (`/usuarios/{user}`) con sus productos
- Los hilos de chat de todos los usuarios son visibles para el administrador

---

## Instrucciones de instalación con Docker

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

Edita `.env` si necesitas cambiar algún valor (base de datos, mail, zona horaria, etc.).

Asegúrate de tener la zona horaria correcta:

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

> El proyecto tiene varios entry points de Vite: `app.js` (global), `dashboard.js` (panel de gestión) y `auth.js` (formularios de autenticación).

---

## Servicios disponibles

Una vez levantado Docker, accede desde el navegador:

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

## Roles y administración

El sistema distingue dos tipos de usuario: **usuarios registrados** y **administradores**.

### Campo `is_admin`

La tabla `users` tiene una columna booleana `is_admin` (por defecto `false`). Solo los usuarios con `is_admin = true` pueden acceder al panel de gestión.

Los usuarios registrados desde el formulario de registro **nunca** obtienen acceso de administrador automáticamente.

### Dar permisos de administrador a un usuario

Desde Tinker:

```bash
./vendor/bin/sail artisan tinker
```

```php
User::where('email', 'correo@ejemplo.com')->update(['is_admin' => true]);
```

O directamente en phpMyAdmin: pon `is_admin = 1` en el registro del usuario.

### Qué ve cada usuario

| Sección                              | Visitante | Usuario normal | Administrador |
|--------------------------------------|:---------:|:--------------:|:-------------:|
| Tienda pública                       | Sí        | Sí             | Sí            |
| Búsqueda y filtros drill-down        | Sí        | Sí             | Sí            |
| Ver perfil público de un usuario     | Sí        | Sí             | Sí            |
| Dar like a productos                 | No        | Sí             | Sí            |
| Mis favoritos                        | No        | Sí             | Sí            |
| Contactar con el vendedor (chat)     | No        | Sí             | Sí            |
| Mis mensajes + notificaciones        | No        | Sí             | Sí (todos)    |
| Publicar productos                   | No        | Sí             | Sí            |
| Marcar producto como vendido         | No        | Sí (propio)    | Sí            |
| Mi perfil (editar datos)             | No        | Sí             | Sí            |
| Panel de gestión                     | No        | No             | Sí            |
| CRUD productos y categorías          | No        | No             | Sí            |

---

## Rutas principales

| Método | Ruta                                     | Descripción                                    |
|--------|------------------------------------------|------------------------------------------------|
| GET    | `/`                                      | Catálogo de productos                          |
| GET    | `/producto/{product}`                    | Detalle del producto                           |
| PATCH  | `/producto/{product}/vendido`            | Marcar producto como vendido (dueño)           |
| PATCH  | `/producto/{product}/reactivar`          | Volver a poner en venta (dueño)                |
| GET    | `/mis-favoritos`                         | Productos marcados con like                    |
| GET    | `/publicar`                              | Formulario para publicar un nuevo producto     |
| POST   | `/publicar`                              | Guardar nuevo producto                         |
| GET    | `/mis-mensajes`                          | Lista de conversaciones (comprador + vendedor) |
| GET    | `/chat/{product}`                        | Chat del comprador con el vendedor             |
| GET    | `/chat/{product}/vendedor/{buyer}`       | Hilo del vendedor con un comprador concreto    |
| GET    | `/chat/{product}/{user}`                 | Hilo específico (solo admin)                   |
| GET    | `/mensajes/no-leidos`                    | API: devuelve nº de mensajes no leídos (JSON)  |
| GET    | `/mi-perfil`                             | Perfil propio (editar datos + mis productos)   |
| GET    | `/usuarios/{user}`                       | Perfil público de cualquier usuario            |
| GET    | `/dashboard`                             | Panel de gestión (solo admin)                  |

---

## Backup de la base de datos

El proyecto incluye un comando artisan para generar backups completos de la BD. Los archivos se guardan en `storage/backups/` con el nombre `backup_YYYYMMDD_HHMMSS.sql`.

```bash
./vendor/bin/sail artisan backup:database
```

**Recomendación:** haz un backup antes de cualquier cambio importante en la BD o antes de cambiar de equipo.

El comando guarda automáticamente solo los **últimos 5 backups** — cuando se crea el sexto, elimina el más antiguo.

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
./vendor/bin/sail artisan route:clear

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
