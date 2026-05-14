<p align="center">
  <img src="public/images/logo.svg" alt="Venalia" height="80">
</p>

# Venalia — Plataforma de compra y venta

Venalia es una aplicación web de compra-venta donde cualquier usuario registrado puede publicar productos, contactar con vendedores mediante chat en tiempo real y guardar sus favoritos.

---

## Índice

- [Funcionalidades principales](#funcionalidades-principales)
  - [Tienda pública](#tienda-pública)
  - [Perfiles públicos de usuario](#perfiles-públicos-de-usuario)
  - [Estados de un producto](#estados-de-un-producto)
  - [Sistema de ventas y reservas](#sistema-de-ventas-y-reservas)
  - [Sistema de likes (favoritos)](#sistema-de-likes-favoritos)
  - [Chat entre usuarios](#chat-entre-usuarios-comprador--vendedor)
  - [Lista de conversaciones ("Mis mensajes")](#lista-de-conversaciones-mis-mensajes)
  - [Notificaciones de mensajes no leídos](#notificaciones-de-mensajes-no-leídos)
  - [Sistema de valoraciones](#sistema-de-valoraciones)
  - [Verificación de email](#verificación-de-email)
  - [Publicar productos](#publicar-productos-todos-los-usuarios)
  - [Perfil de usuario](#perfil-de-usuario)
  - [Consentimiento de cookies y analítica](#consentimiento-de-cookies-y-analítica)
  - [Navegación móvil (bottom navigation)](#navegación-móvil-bottom-navigation)
  - [Dark mode](#dark-mode)
  - [Footer legal](#footer-legal)
  - [Panel de administración](#panel-de-administración)
- [Instalación con Docker](#instrucciones-de-instalación-con-docker)
  - [Requisitos previos](#requisitos-previos)
  - [1. Clonar el repositorio](#1-clonar-el-repositorio)
  - [2. Instalar dependencias de PHP](#2-instalar-dependencias-de-php)
  - [3. Configurar el entorno](#3-configurar-el-entorno)
  - [4. Levantar Docker](#4-levantar-docker)
  - [5. Generar clave, migrar y enlazar storage](#5-generar-clave-migrar-y-enlazar-storage)
  - [6. Instalar dependencias de Node y compilar assets](#6-instalar-dependencias-de-node-y-compilar-assets)
- [Servicios disponibles](#servicios-disponibles)
- [Roles y administración](#roles-y-administración)
- [Rutas principales](#rutas-principales)
- [Backup de la base de datos](#backup-de-la-base-de-datos)
- [Tests](#tests)
- [Comandos útiles](#comandos-útiles)
- [Mensaje diario automático](#mensaje-diario-automático)
- [Problemas frecuentes en Windows](#problemas-frecuentes-en-windows)
- [Despliegue en AWS Cloud9](#despliegue-en-aws-cloud9)
  - [Requisitos de la Instancia](#requisitos-de-la-instancia)
  - [Solución de errores comunes](#solución-de-errores-comunes-en-cloud9)
  - [Proceso de instalación rápida](#proceso-de-instalación-rápida-en-cloud9)
  - [Configuración .env para AWS](#notas-importantes-de-configuración-para-aws)

---

## Funcionalidades principales

### Tienda pública
- Catálogo de productos con **panel de filtros lateral** (drawer) accesible desde el botón "Filtros" con badge que muestra el número de filtros activos:
  - **Ordenar por**: más recientes, precio menor a mayor, precio mayor a menor
  - **Rango de precio**: inputs Mín/Máx con debounce — filtra en tiempo real sin recargar
  - **Categoría**: filtrado en modo **drill-down** reactivo (seleccionas una categoría raíz y aparecen sus subcategorías, luego las de estas, y así sucesivamente)
  - Botón "Limpiar" que resetea todos los filtros y "Ver X resultados" que cierra el panel
  - El drawer se adapta a móvil teniendo en cuenta el navbar inferior
- **Carga infinita proactiva (Infinite Scroll)**:
  - Carga inicial de **30 productos** (múltiplo de 3 para mantener la simetría de la grid).
  - **Disparador por índice**: el sistema detecta mediante `IntersectionObserver` cuándo el usuario llega al producto número 15 (contando desde el final) y solicita automáticamente los siguientes 30.
  - Este enfoque elimina los tiempos de espera visuales, ya que el contenido se descarga mientras el usuario aún está viendo el "colchón" de los últimos 15 productos cargados.
- Búsqueda por nombre/descripción desde el buscador del navbar: Alpine despacha un evento al window con debounce de 300 ms, el componente Livewire lo escucha y actualiza la propiedad directamente sin recargar la página
- Los productos en estado **activo** y **reservado** aparecen en la tienda; los vendidos o inactivos no
- URLs amigables basadas en slug: `/producto/iphone-14-pro` en lugar de `/producto/1` — generadas automáticamente con `spatie/laravel-sluggable` (tildes y caracteres especiales normalizados)
- Galería de imágenes con lightbox y navegación por teclado en el detalle del producto
- Nombre del autor visible en cada tarjeta y en el detalle, **clickable** — lleva al perfil público del vendedor

### Perfiles públicos de usuario
- Cualquier visitante puede ver el perfil de un vendedor en `/usuarios/{user}`
- Muestra foto/inicial, nombre, fecha de registro, número de productos en venta, **valoración media** y la grid de productos
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
- La cabecera del chat muestra el nombre del producto y el nombre de la contraparte (comprador ve al vendedor, vendedor ve al comprador) — el nombre es un **enlace al perfil público** del usuario
- Las conversaciones se identifican por producto + comprador, de modo que el vendedor puede tener hilos separados con cada interesado
- El vendedor puede ver y responder todos los mensajes recibidos en sus productos desde "Mis mensajes"
- Actualización automática cada 3 segundos (Livewire polling)
- Auto-scroll al mensaje más reciente
- **Separadores de fecha** entre mensajes de días distintos: muestra "Hoy", "Ayer" o la fecha completa en español
- La hora de cada mensaje aparece integrada en el globo al estilo WhatsApp: en la misma línea si el texto es corto, o en la esquina inferior derecha si el texto ocupa varias líneas
- **Sistema de presencia online:** el chat actualiza silenciosamente la actividad del usuario cada 3 segundos; si el destinatario lleva más de 30 segundos sin tener el chat abierto se le envía una notificación por correo al recibir un mensaje nuevo
- **Acciones del vendedor desde el chat:** el dueño del producto puede **reservarlo**, **cancelar la reserva** o **marcarlo como vendido** directamente desde la cabecera del chat, sin salir de la conversación. El cambio a "Vendido" requiere confirmación mediante un modal
- **Modal de valoración post-venta:** al marcar un producto como vendido, el vendedor ve inmediatamente un modal para valorar al comprador (1–5 estrellas). El comprador, la próxima vez que abra ese chat, también ve el modal para valorar al vendedor. Cada parte solo puede valorar una vez por transacción; el modal se puede omitir

### Lista de conversaciones ("Mis mensajes")

La lista diferencia visualmente el estado de cada producto:

| Estado | Imagen | Badge | Nombre |
|--------|--------|-------|--------|
| Normal | Color | — | Normal |
| Reservado | Gris leve | Ámbar "RESERVADO" | Normal |
| Vendido | Gris 60% | Rojo "VENDIDO" | Tachado |
| Eliminado | Placeholder | — | "Producto eliminado" (no clicable) |

### Notificaciones de mensajes no leídos
- **Punto rojo en el avatar** del navbar — aparece automáticamente en menos de 5 segundos cuando llega un mensaje nuevo, sin necesidad de recargar la página (polling JS)
- **Badge con número** en el menú desplegable junto a "Mis mensajes"
- **Indicador visual** en la lista de conversaciones: texto en negrita + punto azul en los hilos con mensajes sin leer
- **Notificación por correo** cuando el destinatario lleva más de 30 segundos sin tener el chat abierto
- Funciona tanto para compradores (cuando el vendedor responde) como para vendedores (cuando un comprador escribe)
- Totalmente adaptado a **dark mode**

### Sistema de valoraciones
- Al completar una venta, comprador y vendedor pueden valorarse mutuamente del **1 al 5 estrellas**
- La media de valoraciones recibidas aparece en el **perfil público** del usuario y en **Mi perfil**
- También es visible para el administrador en el panel de gestión dentro del perfil detallado de cada usuario
- Un usuario solo puede valorar una vez por transacción (constraint único en BD)
- La valoración es opcional — se puede omitir con el enlace "Omitir valoración"
- Sin valoraciones se muestra "—" con estrella gris; en cuanto hay al menos una se muestra la media con estrella dorada

### Verificación de email
- Al registrarse, el usuario recibe un correo de verificación personalizado con el estilo de Venalia (botón dorado, texto en español)
- Un **banner sticky ámbar** informa al usuario de que debe verificar su correo; incluye un botón "Reenviar email de verificación" con **cooldown de 60 segundos** para evitar spam (el temporizador persiste entre recargas de página usando `localStorage`)
- Los usuarios sin email verificado **no pueden publicar productos** — son redirigidos con un mensaje de error
- Tras verificar, se redirige a la tienda con un banner de éxito
- Los banners de éxito y error se cierran automáticamente tras 5 segundos (o manualmente con la X)

### Publicar productos (todos los usuarios)
- Cualquier usuario registrado **y con email verificado** puede publicar productos desde el menú de usuario ("Publicar producto") o desde su perfil ("Añadir producto")
- Formulario con subida de múltiples imágenes (JPEG/PNG, máx. 2 MB c/u), categoría, precio y descripción
- Los productos publicados aparecen en la tienda pública con estado `activo`

### Perfil de usuario
- Página `/mi-perfil` con edición de nombre, email, contraseña y **foto de perfil** (avatar)
- El avatar se puede cambiar pasando el cursor sobre la foto y haciendo clic; también se puede eliminar con la X roja
- Muestra la **valoración media** recibida (estrella dorada + número + total de valoraciones)
- Sección "Mis productos" con las publicaciones propias, incluyendo badge de estado en los reservados/vendidos

### Consentimiento de cookies y analítica

- Banner modal bloqueante que aparece en la primera visita a la tienda — el usuario no puede navegar hasta aceptar o rechazar
- La decisión se guarda en `localStorage` (`cookies_accepted = 1` o `0`) y no vuelve a aparecer
- El scroll del fondo queda bloqueado mientras el modal está visible
- **Google Analytics 4** integrado con consentimiento: el script de GA4 solo se carga si el usuario acepta las cookies, nunca si rechaza
- GA4 recibe información del estado de sesión: `user_id` y `logged_in: true` para usuarios autenticados, `logged_in: false` para visitantes anónimos
- El banner solo aparece en la tienda pública (`store.blade.php`), nunca en el panel de administración

### Navegación móvil (bottom navigation)

- Barra fija en la parte inferior de la pantalla, visible **solo en móvil** (`sm:hidden`) y únicamente para usuarios autenticados
- Sustituye al menú desplegable de usuario del navbar en pantallas pequeñas
- Cinco accesos directos: **Favoritos**, **Mensajes** (con badge de no leídos), **Publicar** (botón central destacado en dorado, elevado), **Mis productos** y **Mi perfil**
- El ítem activo se resalta con color según la sección (rojo en favoritos, índigo en mensajes, dorado en publicar y productos)
- Respeta el safe area de iOS (`env(safe-area-inset-bottom)`) para no quedar tapada por la barra de gestos del iPhone
- El badge de mensajes no leídos se actualiza junto con el resto de indicadores de la app

### Dark mode

- Funciona en todos los layouts: tienda, panel de administración y formularios de autenticación
- La preferencia se guarda en `localStorage` con la clave `theme` (`dark` o `light`)
- Si no hay preferencia guardada, respeta el ajuste del sistema operativo (`prefers-color-scheme`)
- El script de inicialización está inline en el `<head>` para evitar el parpadeo blanco antes de que cargue el JS — centralizado en `resources/views/partials/dark-mode-init.blade.php` e incluido en los tres layouts con `@include`
- `toggleTheme()` está definido en `resources/js/theme.js` y expuesto en `window` para poder llamarlo desde cualquier botón con `onclick="toggleTheme()"`
- Durante la navegación con Livewire (`wire:navigate`), se deshabilitan temporalmente las transiciones CSS para evitar el destello blanco al cambiar de página

### Footer legal

- El footer de la tienda incluye cuatro enlaces legales: **Aviso Legal**, **Política de Privacidad**, **Política de Cookies** y **Términos y Condiciones**
- Cada enlace abre un modal Alpine.js con el contenido correspondiente — sin navegación a otra página
- El scroll del fondo queda bloqueado mientras el modal está abierto (mismo mecanismo que el cookie banner)
- El overlay del modal se puede pulsar para cerrarlo, o usar el botón "Cerrar"
- El contenido de cada documento legal vive en partials Blade separados en `resources/views/partials/legal/`

### Panel de administración
- Solo accesible para usuarios con `is_admin = true`. Todas las rutas del panel usan el prefijo `/admin/`.
- **Dashboard con estadísticas detalladas** (`/admin/dashboard`):
  - Totales por estado: activos, reservados, vendidos, inactivos.
  - Gráfico de barras interactivo (Chart.js) que muestra la distribución de productos por categorías padre.
  - Tabla comparativa detallada con el desglose numérico de estados por cada categoría raíz.
  - Mensaje motivacional del día con sistema de generación automática.
  - Últimos 5 productos publicados.
  - Auto-refresco inteligente (polling) para mantener los datos siempre actualizados.
- **Gestión avanzada de productos** (`/admin/products`):
  - Buscador en tiempo real por nombre y descripción siempre visible (debounce 300 ms)
  - **Panel de filtros lateral** (drawer) con badge de filtros activos:
    - **Ordenar por**: más recientes, precio asc/desc
    - **Estado**: Todos / Activo / Reservado / Vendido / Inactivo
    - **Rango de precio**: Mín/Máx con debounce
    - **Categoría**: drill-down por niveles igual que en la tienda
    - Botones "Limpiar" y "Ver X resultados"
  - Contador total de productos visibles según los filtros aplicados
  - **Paginación infinita proactiva**: carga de 30 productos con disparador dinámico en el ítem 15 antes del final para asegurar una navegación fluida sin esperas.
- **Categorías** (`/admin/categories`): CRUD completo con jerarquía padre-hijo y filtro drill-down reactivo
- **Gestión de usuarios** (`/admin/users`): Sección dedicada con dos pestañas **sin cambio de URL** (Alpine.js):
  - **Clientes** — tabla paginada con nombre, email, fecha de registro, último acceso y estado online. Acciones para ver perfil o eliminar la cuenta (con modal de confirmación)
  - **Administradores** — cards con avatar, badge de rol y acciones (con modales Alpine) para degradar a cliente o eliminar. No se puede modificar la propia cuenta desde el panel
  - **Perfil detallado** (`/admin/users/{id}`) — estadísticas del usuario: productos publicados, favoritos, último acceso, email verificado y **valoración media**. Acciones: editar datos, promover/degradar rol de administrador y eliminar cuenta, todas con modales de confirmación. Incluye un listado de todas las conversaciones del usuario con enlace a cada hilo. La flecha "atrás" navega siempre a la lista de usuarios
  - **Visor de conversación** (`/admin/users/{user}/conversacion/{product}`) — vista de solo lectura del hilo de chat entre un comprador y un vendedor, dentro del layout del panel. Muestra separadores de fecha (Hoy/Ayer/fecha completa) y la hora integrada en cada burbuja. Incluye botón flotante para bajar al final de la conversación
  - **Crear usuario** — formulario dedicado para crear usuarios desde el panel con nombre, email, contraseña y opción de asignar rol de administrador
  - **Editar usuario** — formulario para modificar nombre, email y contraseña (dejar en blanco para no cambiarla); el rol de administrador también se puede cambiar desde aquí (excepto en la propia cuenta)
- **Perfil del administrador** (`/admin/profile`): misma interfaz de edición que el lado cliente (avatar, nombre, email, contraseña, valoración) pero dentro del layout del panel con sidebar
- **Chat del administrador:** el administrador tiene su propio "Mis mensajes" como cualquier usuario — solo ve sus propias conversaciones como comprador o vendedor. Para supervisar las conversaciones de otro usuario, accede a su perfil detallado en el panel

---

## Instrucciones de instalación con Docker

> **Todos los comandos de esta guía se ejecutan desde la terminal de Ubuntu/WSL2 (Windows) o terminal normal (macOS/Linux). Nunca desde CMD ni PowerShell.**

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

> A partir de aquí, **todos los comandos se ejecutan en la terminal de Ubuntu/WSL2** (búscala en el buscador de Windows como "Ubuntu").

3. **Git** — dentro de la terminal de Ubuntu:
   ```bash
   sudo apt update && sudo apt install git -y
   ```

4. **Node.js** — dentro de la terminal de Ubuntu:
   ```bash
   curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
   sudo apt install -y nodejs
   ```

5. **Configura Git para line endings** — evita problemas con los saltos de línea de Windows:
   ```bash
   git config --global core.autocrlf input
   ```


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
./vendor/bin/sail artisan db:seed --class=ProductDemoSeeder
```

El seeder crea:
- Jerarquía de categorías de hasta 4 niveles (Electrónica, Hogar, Moda, Deporte, Vehículos, Coleccionismo y sus subcategorías)
- 5 usuarios de prueba y el administrador `francisco@gmail.com` (contraseña: `1234567890#`)
- ~150 productos distribuidos entre los usuarios, con imágenes descargadas automáticamente de Flickr según la categoría
- Likes cruzados entre usuarios y conversaciones de ejemplo

> **Aviso:** **Nunca uses `migrate:fresh --seed`** en un entorno con datos reales porque borra toda la base de datos.

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

> El proyecto tiene varios entry points de Vite: `app.js` (global), `dashboard.js` (panel de gestión), `auth.js` (formularios de autenticación) y `admin-users.js` (formularios de creación/edición de usuarios en el panel).

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

**Desde el panel de administración** — la forma más fácil. Ve a `localhost/admin/users`, pestaña "Clientes", entra al perfil del usuario y pulsa el botón "Hacer administrador".

**Desde Tinker** — útil para crear el primer administrador cuando aún no hay ninguno:

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
| Búsqueda y filtros (precio, categoría)| Sí       | Sí             | Sí            |
| Ver perfil público de un usuario     | Sí        | Sí             | Sí            |
| Dar like a productos                 | No        | Sí             | Sí            |
| Mis favoritos                        | No        | Sí             | Sí            |
| Contactar con el vendedor (chat)     | No        | Sí             | Sí            |
| Mis mensajes + notificaciones        | No        | Sí             | Sí            |
| Publicar productos                   | No        | Sí             | Sí            |
| Reservar / cancelar reserva          | No        | Sí (propio)    | Sí            |
| Marcar producto como vendido         | No        | Sí (propio)    | Sí            |
| Reactivar producto                   | No        | Sí (propio)    | Sí            |
| Valorar una transacción              | No        | Sí             | Sí            |
| Mi perfil (editar datos + avatar)    | No        | Sí             | Sí            |
| Panel de gestión                     | No        | No             | Sí            |
| CRUD productos y categorías          | No        | No             | Sí            |
| Gestión de usuarios (panel admin)    | No        | No             | Sí            |
| Crear / editar usuarios (panel admin)| No        | No             | Sí            |

---

## Rutas principales

| Método | Ruta                                     | Descripción                                    |
|--------|------------------------------------------|------------------------------------------------|
| GET    | `/`                                      | Catálogo de productos                          |
| GET    | `/producto/{slug}`                       | Detalle del producto (URL amigable por slug)   |
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
| GET    | `/mi-perfil`                             | Perfil propio (editar datos, avatar, valoración) |
| GET    | `/usuarios/{user}`                       | Perfil público de cualquier usuario            |
| GET    | `/admin/dashboard`                       | Panel de gestión (solo admin)                  |
| GET    | `/admin/profile`                         | Perfil del administrador (solo admin)          |
| GET    | `/admin/products`                        | Lista de productos (solo admin)                |
| GET    | `/admin/products/create`                 | Formulario para crear un producto (solo admin) |
| GET    | `/admin/categories`                      | Lista de categorías (solo admin)               |
| GET    | `/admin/stats`                           | Estadísticas con gráficas (solo admin)         |
| GET    | `/admin/users`                           | Lista de usuarios — clientes y admins          |
| GET    | `/admin/users/create`                    | Formulario para crear un nuevo usuario         |
| POST   | `/admin/users`                           | Guardar nuevo usuario                          |
| GET    | `/admin/users/{user}`                    | Perfil detallado de un usuario                 |
| GET    | `/admin/users/{user}/edit`               | Formulario para editar un usuario              |
| PUT    | `/admin/users/{user}`                    | Actualizar datos de un usuario                 |
| PATCH  | `/admin/users/{user}/toggle-admin`       | Promover a admin o degradar a cliente          |
| DELETE | `/admin/users/{user}`                    | Eliminar cuenta de usuario                     |
| GET    | `/admin/users/{user}/conversacion/{product}` | Ver conversación de un usuario (solo admin) |

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
cd /ruta-del-proyecto && php artisan schedule:run >> /dev/null 2>&1
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

---

## Despliegue en AWS Cloud9

Esta guía documenta los pasos necesarios para desplegar el proyecto en un entorno de **AWS Cloud9 (Amazon Linux 2023)**.

### Requisitos de la Instancia
- **Tipo de instancia:** Mínimo `t3.small` (2GB RAM).
- **Disco:** Mínimo **20GB** (El valor por defecto de 10GB es insuficiente para Docker).

### Solución de errores comunes en Cloud9

#### 1. docker-compose: command not found
Cloud9 usa `docker compose` (plugin), pero Sail busca el binario `docker-compose`.
```bash
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

#### 2. buildx version incompatible
Si falla la construcción de la imagen de PHP 8.5:
```bash
mkdir -p ~/.docker/cli-plugins
curl -L https://github.com/docker/buildx/releases/download/v0.17.1/buildx-v0.17.1.linux-amd64 -o ~/.docker/cli-plugins/docker-buildx
chmod +x ~/.docker/cli-plugins/docker-buildx
```

#### 3. no space left on device
Si te quedas sin espacio tras ampliar el volumen en la consola de AWS:
```bash
sudo growpart /dev/xvda 1
sudo xfs_growfs -d /
```

### Proceso de instalación rápida en Cloud9

```bash
# 1. Instalar dependencias iniciales
docker run --rm -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php84-composer:latest composer install --ignore-platform-reqs

# 2. Configurar el entorno, levantar y preparar
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail artisan storage:link

# 3. Frontend (Generar archivos estáticos)
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### Notas importantes de configuración para AWS

Si vas a usar el proyecto en AWS Cloud9, ten en cuenta realizar estos ajustes en tu archivo `.env` para asegurar el correcto funcionamiento y la mejor fluidez:

- **URL y Red:** Configura `APP_URL=http://<tu-ip-publica>` y `VITE_HMR_HOST=<tu-ip-publica>` para que los assets y las rutas funcionen correctamente.
- **Rendimiento:** Cambia `APP_DEBUG=false` para liberar recursos de la CPU.
- **Drivers:** Se recomienda usar `SESSION_DRIVER=file` y `CACHE_STORE=file` para mayor velocidad en instancias con recursos limitados.
- **Seguridad:** Recuerda abrir los puertos **80** (HTTP), **8080** (phpMyAdmin) y opcionalmente el **5173** (Vite) en el Security Group de tu instancia EC2.

---

### Creado por Francisco Aybar
s rutas funcionen correctamente.
- **Rendimiento:** Cambia `APP_DEBUG=false` para liberar recursos de la CPU.
- **Drivers:** Se recomienda usar `SESSION_DRIVER=file` y `CACHE_STORE=file` para mayor velocidad en instancias con recursos limitados.
- **Seguridad:** Recuerda abrir los puertos **80** (HTTP), **8080** (phpMyAdmin) y opcionalmente el **5173** (Vite) en el Security Group de tu instancia EC2.

---

### Creado por Francisco Aybar
