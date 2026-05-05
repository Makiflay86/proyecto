<p align="center">
  <img src="public/images/logo.svg" alt="Venalia" height="80">
</p>

# Venalia — Plataforma de compra y venta

Venalia es una aplicación web de compra-venta donde cualquier usuario registrado puede publicar productos, contactar con vendedores mediante chat en tiempo real y guardar sus favoritos.

---

## Funcionalidades principales

### Tienda pública
- Catálogo de productos con filtrado por categoría en modo **drill-down** reactivo (Livewire): seleccionas una categoría raíz y aparecen sus subcategorías, luego las de estas, y así sucesivamente
- Búsqueda por nombre/descripción integrada en el catálogo (reactiva, sin recargar la página)
- Ordenación por precio (asc/desc) o por más recientes, reactiva sin recargar la página
- Paginación de 12 productos por página
- Los productos en estado **activo** y **reservado** aparecen en la tienda; los vendidos o inactivos no
- Galería de imágenes con lightbox y navegación por teclado en el detalle del producto
- Nombre del autor visible en cada tarjeta y en el detalle, **clickable** — lleva al perfil público del vendedor

### Perfiles públicos de usuario
- Cualquier visitante puede ver el perfil de un vendedor en `/usuarios/{user}`
- Muestra foto/inicial, nombre, fecha de registro, número de productos en venta y la grid de productos
- Si es tu propio perfil, aparece un botón "Editar perfil"

### Estados de un producto

Cada producto puede estar en uno de estos cuatro estados:

| Estado      | Visible en tienda | Descripción                                              |
|-------------|:-----------------:|----------------------------------------------------------|
| `activo`    | Sí                | En venta normalmente                                     |
| `reservado` | Sí (con badge)    | Pendiente de recogida; sigue visible pero marcado        |
| `vendido`   | No                | Cerrado; su página sigue accesible con badge rojo        |
| `inactivo`  | No                | Oculto por el dueño sin marcarlo como vendido            |

### Sistema de ventas y reservas
- El dueño de un producto puede **reservarlo** desde la página del producto (indica que hay trato pendiente)
- Los productos reservados siguen apareciendo en la tienda con un badge visual
- El dueño puede **cancelar la reserva** y volver el producto a activo
- El dueño puede **marcar como vendido** el producto (activo o reservado)
- Los productos vendidos desaparecen de la tienda pero su página sigue accesible con un badge rojo "Vendido"
- En "Mis productos" los vendidos aparecen con badge rojo y la imagen a media opacidad
- El dueño puede **reactivar** un producto vendido o inactivo en cualquier momento
- Todos los cambios de estado son reactivos via Livewire — sin recargar la página
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
- **Separadores de fecha** entre mensajes de días distintos: muestra "Hoy", "Ayer" o la fecha completa en español
- La hora de cada mensaje aparece integrada en el globo al estilo WhatsApp: en la misma línea si el texto es corto, o en la esquina inferior derecha si el texto ocupa varias líneas
- **Sistema de presencia online:** el chat actualiza silenciosamente la actividad del usuario cada 3 segundos; si el destinatario lleva más de 30 segundos sin tener el chat abierto se le envía una notificación por correo al recibir un mensaje nuevo

### Notificaciones de mensajes no leídos
- **Punto rojo en el avatar** del navbar — aparece automáticamente en menos de 5 segundos cuando llega un mensaje nuevo, sin necesidad de recargar la página (polling JS)
- **Badge con número** en el menú desplegable junto a "Mis mensajes"
- **Indicador visual** en la lista de conversaciones: texto en negrita + punto azul en los hilos con mensajes sin leer
- **Notificación por correo** cuando el destinatario lleva más de 30 segundos sin tener el chat abierto
- Funciona tanto para compradores (cuando el vendedor responde) como para vendedores (cuando un comprador escribe)
- Totalmente adaptado a **dark mode**

### Publicar productos (todos los usuarios)
- Cualquier usuario registrado puede publicar productos desde el menú de usuario ("Publicar producto") o desde su perfil ("Añadir producto")
- Formulario con subida de múltiples imágenes (JPEG/PNG, máx. 2 MB c/u), categoría, precio y descripción
- Los productos publicados aparecen en la tienda pública con estado `activo`

### Perfil de usuario
- Página `/mi-perfil` con edición de nombre, email y contraseña
- Sección "Mis productos" con las publicaciones propias, incluyendo badge de estado en los reservados/vendidos

### Panel de administración
- Solo accesible para usuarios con `is_admin = true`.
- **Dashboard con estadísticas detalladas:**
  - Totales por estado: activos, reservados, vendidos, inactivos.
  - Gráfico de barras interactivo (Chart.js) que muestra la distribución de productos por categorías padre.
  - Tabla comparativa detallada con el desglose numérico de estados por cada categoría raíz.
  - Mensaje motivacional del día con sistema de generación automática.
  - Últimos 5 productos publicados.
  - Auto-refresco inteligente (polling) para mantener los datos siempre actualizados.
- **Gestión avanzada de productos:**
  - Lista ordenada por fecha de publicación (más recientes primero) de forma predeterminada
  - Filtro de ordenación por precio (menor/mayor) y fecha
  - Contador total de productos visibles según los filtros aplicados
  - Paginación integrada para una navegación fluida entre grandes catálogos
- **Categorías:** CRUD completo con jerarquía padre-hijo y filtro drill-down reactivo
- **Usuarios:** Vista de perfil de cualquier usuario (`/usuarios/{user}`) con sus productos
- **Chat:** Los hilos de chat de todos los usuarios son visibles para el administrador para fines de soporte o moderación

---

## Instrucciones de instalación con Docker

> **Todos los comandos de esta guía se ejecutan desde la terminal de WSL2 (Windows) o terminal normal (macOS/Linux). Nunca desde CMD ni PowerShell.**

---

## Requisitos previos

### Windows

1. **WSL2** — abre PowerShell **como administrador** y ejecuta:
   ```powershell
   wsl --install
   ```
   Reinicia el equipo. Instala Ubuntu por defecto.

2. **Docker Desktop** — [Descargar](https://www.docker.com/products/docker-desktop/)
   - Durante la instalación activa **"Use the WSL 2 based engine"**
   - Abre Docker Desktop → Settings → Resources → WSL Integration → activa tu distro de Ubuntu
   - Comprueba que el icono de Docker en la barra de tareas está **verde** antes de continuar

3. **Git** — dentro de la terminal de Ubuntu (WSL2):
   ```bash
   sudo apt update && sudo apt install git -y
   ```

4. **Node.js** — dentro de la terminal de Ubuntu (WSL2):
   ```bash
   curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
   sudo apt install -y nodejs
   ```

5. **Configura Git para line endings** — evita problemas con los saltos de línea de Windows:
   ```bash
   git config --global core.autocrlf input
   ```

> A partir de aquí, **todos los comandos se ejecutan en la terminal de Ubuntu/WSL2** (búscala en el menú inicio como "Ubuntu").

### macOS / Linux

- Docker Desktop instalado y en ejecución
- Git y Node.js instalados

---

## 1. Clonar el repositorio

```bash
git clone https://github.com/Makiflay86/proyecto.git
cd proyecto
```

> **Windows — IMPORTANTE:** clona **dentro** del sistema de archivos de WSL2, no en `/mnt/c/...`.
> La ruta recomendada es `~/proyectos/proyecto` (home de Ubuntu).
> Clonar en `C:\Users\...` y trabajar desde WSL2 es extremadamente lento y Vite no detectará cambios.

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

El `.env.example` ya incluye la configuración correcta para Docker (MySQL, Mailpit). No necesitas editar nada para empezar.

Si quieres cambiar la zona horaria (ya está en `Europe/Madrid` por defecto):

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
| Reservar / cancelar reserva          | No        | Sí (propio)    | Sí            |
| Marcar producto como vendido         | No        | Sí (propio)    | Sí            |
| Reactivar producto                   | No        | Sí (propio)    | Sí            |
| Mi perfil (editar datos)             | No        | Sí             | Sí            |
| Panel de gestión                     | No        | No             | Sí            |
| CRUD productos y categorías          | No        | No             | Sí            |

---

## Rutas principales

| Método | Ruta                                     | Descripción                                    |
|--------|------------------------------------------|------------------------------------------------|
| GET    | `/`                                      | Catálogo de productos                          |
| GET    | `/producto/{product}`                    | Detalle del producto                           |
| PATCH  | `/producto/{product}/reservar`           | Marcar producto como reservado (dueño)         |
| PATCH  | `/producto/{product}/quitar-reserva`     | Cancelar reserva (dueño)                       |
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

### Restaurar un backup

Copia el archivo `.sql` en `storage/backups/` y ejecuta:

```bash
./vendor/bin/sail mysql laravel < storage/backups/backup_YYYYMMDD_HHMMSS.sql
```

Sustituye `backup_YYYYMMDD_HHMMSS.sql` por el nombre real del archivo. Esto carga todos los datos (usuarios, productos, categorías, mensajes...) sin necesidad de ejecutar `db:seed`.

> **Importante:** la restauración sobreescribe los datos existentes. Asegúrate de que los contenedores están levantados (`sail up -d`) antes de ejecutar el comando.

---

## Tests

El proyecto incluye una suite de tests automatizados. Para ejecutarlos necesitas los contenedores levantados (`sail up -d`):

```bash
./vendor/bin/sail test
```

Los tests usan una base de datos separada (`testing`) que se resetea automáticamente en cada ejecución — nunca tocan los datos reales.

### Qué se testea

| Suite | Archivo | Qué cubre |
|---|---|---|
| Unit | `ProductTest` | Fillable, casts, estados, relaciones, cascada |
| Unit | `ProductImageTest` | Fillable, relación con producto, cascada |
| Unit | `CategoryTest` | Mutador de nombre, jerarquía, breadcrumb, flatOptions |
| Unit | `UserTest` | isOnline, mensajes no leídos, likes |
| Feature | `StoreTest` | Catálogo público, detalle, estados visibles, favoritos, perfil público |
| Feature | `PublishControllerTest` | Publicar producto, validaciones, imágenes, cambios de estado |
| Feature | `ChatTest` | Chat comprador/vendedor/admin, API de mensajes no leídos |
| Feature | `ProductControllerTest` | CRUD admin de productos, acceso por rol |
| Feature/Admin | `AdminCategoryTest` | CRUD admin de categorías, jerarquías, imágenes |
| Feature/Auth | `AuthenticationTest` | Login, logout, contraseña incorrecta |
| Feature/Auth | `RegistrationTest` | Registro de nuevo usuario |
| Feature/Auth | `PasswordResetTest` | Recuperación de contraseña |
| Feature/Auth | `PasswordUpdateTest` | Cambio de contraseña |
| Feature | `ProfileTest` | Editar perfil, eliminar cuenta |

---

## Comandos útiles

```bash
# Ver logs en tiempo real
./vendor/bin/sail logs -f

# Abrir una terminal dentro del contenedor
./vendor/bin/sail shell

# Ejecutar comandos artisan
./vendor/bin/sail artisan <comando>

# Ejecutar todos los tests
./vendor/bin/sail test

# Ejecutar solo los tests de una clase
./vendor/bin/sail test --filter ProductControllerTest

# Ejecutar solo los tests de una carpeta
./vendor/bin/sail test tests/Unit
./vendor/bin/sail test tests/Feature/Admin

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

La app genera un mensaje motivacional diario.

- **En desarrollo:** No requiere configuración. El Dashboard detecta si no hay mensaje para hoy y lo genera automáticamente al cargar la página.
- **En producción:** Se recomienda añadir un cron para que se ejecute independientemente de la entrada de usuarios:

```bash
* * * * * cd /ruta-del-proyecto && php artisan schedule:run >> /dev/null 2>&1
```

Para ejecutarlo manualmente en cualquier momento:

```bash
./vendor/bin/sail artisan app:generate-daily-message
```

---

## Problemas frecuentes en Windows

**Docker no arranca / "Cannot connect to the Docker daemon"**
- Abre Docker Desktop y espera a que el icono de la barra de tareas esté **verde**.
- Si sigue fallando: `wsl --update` en PowerShell como administrador y reinicia.

**`./vendor/bin/sail` da "No such file or directory"**
- La carpeta `vendor` no existe aún. Ejecuta primero el paso 2 (instalación de dependencias con Docker).

**`./vendor/bin/sail` da "Permission denied"**
```bash
chmod +x vendor/bin/sail
```

**`sail up` falla con "port is already allocated" en el puerto 80**
- IIS, Skype o algún otro proceso ocupa el puerto 80. Detén ese servicio, o cambia el puerto en `compose.yaml`:
  ```yaml
  ports:
    - '8000:80'   # cambia 80 por otro puerto libre
  ```
  Y actualiza `APP_URL=http://localhost:8000` en `.env`.

**`sail up` falla con "port 3306 already allocated"**
- Tienes MySQL corriendo localmente en Windows. Detén el servicio MySQL de Windows o cambia el puerto en `compose.yaml`:
  ```yaml
  ports:
    - '3307:3306'
  ```

**Los cambios en archivos no se reflejan / Vite no detecta cambios**
- El proyecto **debe** estar clonado dentro de WSL2 (`~/proyectos/...`), nunca en `/mnt/c/...`.
- Desde Windows Explorer puedes acceder a WSL2 escribiendo `\\wsl$\Ubuntu` en la barra de direcciones.

**`npm run dev` da error de EACCES o permisos**
- Ejecuta `npm install` y `npm run dev` **dentro de la terminal WSL2**, nunca desde CMD o PowerShell de Windows.

**La app carga pero las imágenes no aparecen**
```bash
./vendor/bin/sail artisan storage:link
```

**Los scripts de blade/Livewire no cargan (404 en assets)**
```bash
npm run build
```
O si estás en modo desarrollo, asegúrate de tener `npm run dev` corriendo en otra terminal.

**Error "Access denied for user 'sail'@..." al migrar**
- MySQL tarda unos segundos en arrancar. Espera 10 segundos tras `sail up -d` y vuelve a intentarlo.

**Artisan da "No application encryption key"**
```bash
./vendor/bin/sail artisan key:generate
```
