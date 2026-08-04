# Radio Rumbo

Sistema web Radio Rumbo, emisora local de Huancayo, Perú. La Fase 1.1 entrega una GUI conectable y presentable con branding real, contenido base confirmado y datos mock controlados, construida sobre una base PHP simple, segura y compatible con hosting compartido.

## Stack tecnico

- PHP 8.1 o superior
- MySQL con PDO
- HTML, CSS y JavaScript vanilla
- Composer para desarrollo local
- PHPUnit para pruebas
- PHPStan para analisis estatico
- PHPCS con PSR-12 para estilo
- phpdotenv para variables de entorno

Extensiones PHP recomendadas: `pdo_mysql`, `mbstring`, `dom`, `xml`, `xmlwriter`, `SimpleXML` e `iconv`.

## Objetivo del sistema

Construir una plataforma web administrable para una radio local: portada, transmisión en vivo, noticias, programación, equipo, contacto, publicidad y panel administrativo. En Fase 1.1 la interfaz visual queda lista para conectar con PHP/MySQL en fases posteriores, sin implementar todavía CRUDs, login real ni datos dinámicos desde base de datos.

## Instalacion local

1. Instalar PHP 8.1+, Composer y extensiones comunes de PHP: `pdo_mysql`, `mbstring`, `dom`, `xml`, `xmlwriter`, `SimpleXML` e `iconv`.
2. Copiar `.env.example` como `.env`.
3. Ajustar variables locales sin usar credenciales reales de produccion.
4. Instalar dependencias:

```bash
composer install
```

5. Crear la base de datos local. Para MySQL local puede usarse `database/schema.local.sql`; para hosting compartido importar `database/schema.sql` sobre una base ya creada.

## Comandos disponibles

```bash
composer test
composer analyse
composer lint
php -S localhost:8000 -t public
```

- `composer test`: ejecuta PHPUnit.
- `composer analyse`: ejecuta PHPStan sobre `app` y `tests`.
- `composer lint`: ejecuta PHPCS con PSR-12.
- `php -S localhost:8000 -t public`: levanta la interfaz pública y administrativa visual.
- Las rutas visuales preparadas son `/`, `/en-vivo/`, `/programacion/`, `/noticias/`, `/podcasts/`, `/equipo/`, `/publicidad-contacto/` y `/admin/`.

## Estructura del proyecto

```text
app/            Codigo PHP de aplicacion: Core, Controllers, Models, Services, Middleware, Validators y Helpers.
public/         Document root recomendado, entrada publica, /admin, assets y uploads controlados.
admin/          Carpeta interna no publica para preparacion administrativa futura.
config/         Configuracion de aplicacion, base de datos y datos mock de Fase 1.
database/       Esquema, seed y futuras migraciones SQL.
resources/      Layouts, parciales y vistas PHP separadas del document root.
tests/          Pruebas unitarias, integracion y BDD documental.
docs/           Requisitos, arquitectura, seguridad, QA e ISO.
```

## Pruebas

Despues de instalar dependencias, ejecutar:

```bash
composer test
```

La suite incluye pruebas para helpers de seguridad, slugs, roles permitidos, renderizado de vistas y estructura de datos mock.

## Fase 2.0 Backend mínimo

Ya existe funcionalidad real mínima para:

- Conexión MySQL por PDO usando variables de `.env`.
- Login administrativo en `/admin/login/`.
- Dashboard protegido en `/admin/dashboard/`.
- Logout en `/admin/logout/`.
- CRUD mínimo de noticias en `/admin/news/`.
- Noticias públicas dinámicas en `/noticias/`, mostrando solo estado `published`.
- Lectura básica de configuración desde tabla `settings` con fallback seguro.

Para crear el usuario inicial:

1. Importar `database/schema.sql` y luego `database/seed.sql`.
2. Generar un hash local:

```bash
php -r "echo password_hash('CAMBIAR_ESTA_CONTRASENA_LOCALMENTE', PASSWORD_DEFAULT), PHP_EOL;"
```

3. Usar `database/create-initial-user.example.sql` como referencia.
4. Reemplazar el hash placeholder manualmente antes de ejecutar el INSERT.
5. No guardar contraseñas reales ni hashes productivos en Git.

La recuperación de contraseña, usuarios avanzados, uploads, podcasts, ranking, equipo funcional y métricas avanzadas siguen como fases futuras.

## Fase 2.0.1 Medios y usuarios locales

Credenciales locales sugeridas, solo para desarrollo:

- Super Admin: `superadmin.local@radiorumbo.test` / `LocalOnly#2026!`
- Admin: `admin.local@radiorumbo.test` / `AdminLocal#2026!`

Estas credenciales no son de producción, no deben usarse en DonWeb y deben cambiarse antes de publicar.

Para crearlas o actualizarlas en la base local:

```bash
php database/create-local-users.php
```

El script solo corre con `APP_ENV=local`, lee `.env`, usa `password_hash()` y no crea roles nuevos.

Medios configurados:

- Audio en vivo: `https://mediastreamm.com/8008/stream;`
- Video en vivo: `https://servilive.com/live-stream-video-widget/tvrumbo`
- Facebook oficial: `https://www.facebook.com/radiorumboperu`

El audio usa `<audio>` HTML5 y requiere interacción del usuario. El bloque de Facebook usa iframe oficial del plugin de página; puede fallar por cookies, navegador o políticas externas. El sistema no hace scraping ni usa API privada de Facebook.

## Servidor local

```bash
php -S localhost:8000 -t public
```

Abrir `http://localhost:8000`. El panel administrativo placeholder carga desde `http://localhost:8000/admin/`.

La raiz web local y recomendada para DonWeb es siempre `public/`. Las carpetas internas como `app/`, `config/`, `database/`, `docs/`, `resources/`, `tests/` y `vendor/` no deben quedar expuestas al navegador.

## Fase 1.1 GUI

- La home pública ya es una pantalla presentable con hero, logo real, en vivo, noticias, programación, publicidad, equipo y reproductor flotante visual.
- `/admin/` muestra un dashboard administrativo visual con datos mock; `/admin/?view=login` muestra el login visual.
- `config/mock-data.php` centraliza datos base confirmados y placeholders. No contiene datos reales sensibles.
- `public/assets/css/main.css`, `public/assets/css/admin.css`, `public/assets/js/main.js` y `public/assets/js/player.js` concentran estilos e interacciones vanilla.
- El reproductor no conecta aun a un stream real; solo alterna estados visuales.
- Formularios, botones y modulos futuros estan preparados visualmente, sin comportamiento productivo.
- Datos reales confirmados: nombre `Radio Rumbo`, lema `La radio que se ve` y ubicación `Huancayo, Perú`.

## Despliegue en DonWeb

- Publicar solo los archivos necesarios en el hosting.
- Configurar el document root hacia `public/` si el plan lo permite.
- Si DonWeb no permite cambiar el document root, publicar solo el contenido de `public/` como carpeta web y mantener el resto fuera del area publica cuando sea posible.
- Mantener `.env`, `database/`, `docs/`, `resources/`, `tests/`, `vendor/` y archivos de configuracion sensibles fuera de exposicion publica.
- DonWeb puede operar como hosting compartido; no asumir procesos permanentes, colas residentes, WebSockets ni Docker.
- Usar MySQL y PHP 8.1+ disponibles en el panel.
- Crear la base de datos desde el panel DonWeb e importar `database/schema.sql`.
- `vendor/` no se versiona; puede subirse al hosting como artefacto si no se puede ejecutar Composer en produccion.
- Generar credenciales y hashes de contrasena durante instalacion, nunca desde archivos versionados.
- Ver guia completa en `docs/deployment/donweb.md`.
