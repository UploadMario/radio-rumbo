# Radio Rumbo - Auditoria integral del repositorio

Fecha de auditoria: 2026-10-12  
Alcance: diagnostico tecnico, seguridad, CRUD, modelo de datos, frontend publico, administracion y pruebas.  
Regla aplicada: no se modifico logica funcional; solo se crea documentacion de auditoria.

## 1. Baseline tecnico

### HECHO VERIFICADO

Comandos ejecutados en `C:\Continental\radio-rumbo`:

| Comando | Resultado |
|---|---|
| `composer test` | Falla. PHPUnit 10.5.64 reporta: `Cannot open bootstrap script "C:\Continental\radio-rumbo\vendor/autoload.php"` y retorna codigo 2. |
| `composer analyse` | Falla. PHPStan reporta: `File 'C:\Continental\radio-rumbo\phpstan.neon' is missing or is not readable.` y retorna codigo 1. |
| `composer lint` | OK, codigo 0, sin salida de errores. |
| `npm run build -- --clearScreen false` | OK. Vite 8.2.2 compila `admin.css 8.93 kB`, `main.css 43.90 kB`, `admin.js 0.02 kB`, `navigation.js 0.37 kB`, `main.js 68.73 kB`. |

Validacion adicional:

- `vendor/autoload.php` existe y pesa 748 bytes.
- `phpstan.neon` existe y pesa 66 bytes.
- El fallo de `composer test` y `composer analyse` queda como bloqueo de verificacion del entorno PHP/Composer actual, no como evidencia concluyente de falla de codigo.

### PENDIENTE DE PRUEBA

- Repetir `composer test` y `composer analyse` en una terminal limpia o tras revisar permisos/locking del entorno.
- No se ejecuto navegacion manual con navegador real durante esta auditoria.

## 2. Estado del arbol de trabajo

### HECHO VERIFICADO

`git status --short` muestra cambios ya existentes antes de esta auditoria en archivos de frontend, vistas y assets, por ejemplo:

- `resources/views/public/home.php`
- `resources/views/partials/floating-player.php`
- `src/styles/*`
- `public/assets/dist/assets/*`
- assets nuevos en `public/assets/img/`

Esta auditoria no revierte ni interpreta esos cambios como propios.

## 3. Inventario de rutas publicas

### HECHO VERIFICADO

Entrada principal: `public/index.php`.

Rutas registradas en `public/index.php`:

| Ruta | Vista/controlador | Estado |
|---|---|---|
| `/` | `home` por `rr_render_public()` | Implementada |
| `/en-vivo` | `live` por `rr_render_public()` | Implementada |
| `/programacion` | `App\Controllers\Public\ProgramController::index()` | Implementada |
| `/noticias` | `App\Controllers\Public\NewsController::index()` | Implementada |
| `/podcasts` | `podcasts` | Vista planificada, sin funcionalidad real |
| `/equipo` | `App\Controllers\Public\TeamController::index()` | Implementada |
| `/publicidad-contacto` | `advertising-contact` / `ContactController` en entrypoint fisico | Implementada |

Ademas existen entrypoints fisicos:

- `public/en-vivo/index.php`
- `public/programacion/index.php`
- `public/noticias/index.php`
- `public/equipo/index.php`
- `public/publicidad-contacto/index.php`
- `public/podcasts/index.php`

### INFERENCIA

El routing real combina `public/index.php` con entrypoints fisicos por carpeta. `App\Core\Router` existe, pero no es el mecanismo principal observado.

## 4. Inventario administrativo

### HECHO VERIFICADO

Entry points administrativos:

| Ruta | Archivo | Controlador |
|---|---|---|
| `/admin/` | `public/admin/index.php` | `DashboardController::index()` si hay sesion |
| `/admin/login/` | `public/admin/login/index.php` | `AuthController::showLogin()` / `login()` |
| `/admin/logout/` | `public/admin/logout/index.php` | `AuthController::logout()` |
| `/admin/dashboard/` | `public/admin/dashboard/index.php` | `DashboardController::index()` |
| `/admin/news/` | `public/admin/news/index.php` | `NewsController::index()` / `archive()` |
| `/admin/news/create/` | `public/admin/news/create/index.php` | `NewsController::create()` / `store()` |
| `/admin/news/edit/?id=` | `public/admin/news/edit/index.php` | `NewsController::edit()` / `update()` |
| `/admin/programs/` | `public/admin/programs/index.php` | `ProgramController::index()` / `action()` |
| `/admin/programs/create/` | `public/admin/programs/create/index.php` | `ProgramController::create()` / `store()` |
| `/admin/programs/edit/?id=` | `public/admin/programs/edit/index.php` | `ProgramController::edit()` / `update()` |
| `/admin/team/` | `public/admin/team/index.php` | `TeamController::index()` / `action()` |
| `/admin/team/create/` | `public/admin/team/create/index.php` | `TeamController::create()` / `store()` |
| `/admin/team/edit/?id=` | `public/admin/team/edit/index.php` | `TeamController::edit()` / `update()` |
| `/admin/messages/` | `public/admin/messages/index.php` | `MessageController::index()` / `action()` |
| `/admin/messages/view/?id=` | `public/admin/messages/view/index.php` | `MessageController::view()` |
| `/admin/settings/` | `public/admin/settings/index.php` | `SettingController::index()` / `update()` |
| `/admin/banners/` | `public/admin/banners/index.php` | `BannerController::index()` / `action()` |
| `/admin/banners/create/` | `public/admin/banners/create/index.php` | `BannerController::create()` / `store()` |
| `/admin/banners/edit/?id=` | `public/admin/banners/edit/index.php` | `BannerController::edit()` / `update()` |
| `/admin/urgent-notices/` | `public/admin/urgent-notices/index.php` | `UrgentNoticeController::index()` / `action()` |
| `/admin/urgent-notices/create/` | `public/admin/urgent-notices/create/index.php` | `UrgentNoticeController::create()` / `store()` |
| `/admin/urgent-notices/edit/?id=` | `public/admin/urgent-notices/edit/index.php` | `UrgentNoticeController::edit()` / `update()` |

Vistas admin existentes:

- `resources/views/admin/dashboard.php`
- `resources/views/admin/login.php`
- `resources/views/admin/news-form.php`
- `resources/views/admin/news-index.php`
- `resources/views/admin/programs/form.php`
- `resources/views/admin/programs/index.php`
- `resources/views/admin/team/form.php`
- `resources/views/admin/team/index.php`
- `resources/views/admin/messages/index.php`
- `resources/views/admin/messages/view.php`
- `resources/views/admin/settings/index.php`
- `resources/views/admin/banners/form.php`
- `resources/views/admin/banners/index.php`
- `resources/views/admin/urgent-notices/form.php`
- `resources/views/admin/urgent-notices/index.php`

## 5. Controladores, servicios, modelos y validadores

### HECHO VERIFICADO

Controladores publicos:

- `App\Controllers\Public\NewsController`
- `App\Controllers\Public\ProgramController`
- `App\Controllers\Public\TeamController`
- `App\Controllers\Public\ContactController`

Controladores admin:

- `AuthController`
- `DashboardController`
- `NewsController`
- `ProgramController`
- `TeamController`
- `MessageController`
- `SettingController`
- `BannerController`
- `UrgentNoticeController`

Servicios principales:

- `AuthService`
- `SessionService`
- `LoginRateLimiterService`
- `LoggerService`
- `AuditLogService`
- `SettingService`
- `NewsService`
- `ProgramService`
- `ProgramScheduleService`
- `TeamService`
- `ContactMessageService`
- `BannerService`
- `UrgentNoticeService`

Modelos:

- `User`
- `Role`
- `Setting`
- `News`
- `Program`
- `TeamMember`
- `ContactMessage`
- `Banner`
- `UrgentNotice`

Validadores:

- `LoginValidator`
- `NewsValidator`
- `ProgramValidator`
- `TeamMemberValidator`
- `ContactMessageValidator`
- `SettingValidator`
- `BannerValidator`
- `UrgentNoticeValidator`

### INFERENCIA

La arquitectura se mantiene como MVC simple con Services y Models por modulo. No se detecto framework pesado ni SPA.

## 6. Autenticacion, sesiones y autorizacion

### HECHO VERIFICADO

- `User::findActiveByIdentifier()` usa `users.status = 'active'` y JOIN con `roles` por `role_id`.
- `AuthService::attempt()` usa `password_verify()` y valida rol contra `Role::allowed()`.
- Roles permitidos en `Role`: `Super Admin` y `Admin`.
- `SessionService::login()` regenera ID de sesion y guarda `last_activity`.
- `SessionService::user()` aplica timeout por `ADMIN_SESSION_TIMEOUT`.
- `AuthController::logout()` exige `POST` y CSRF.
- `resources/views/layouts/admin.php` usa `<meta name="robots" content="noindex, nofollow">`.

### INFERENCIA

La proteccion de rutas admin depende de que cada controlador invoque su propio `requireUser()`. Existe `AuthMiddleware`, pero el routing observado no lo usa como capa global.

### PENDIENTE DE PRUEBA

- Verificar HTTP real de cada ruta admin sin sesion.
- Verificar permisos por rol en cada operacion. La auditoria de codigo muestra `SettingController` restringe edicion a `Super Admin`, pero otros modulos aceptan ambos roles si hay sesion.

## 7. Modelo de datos

### HECHO VERIFICADO

Tablas en `database/schema.sql`:

- `roles`
- `users`
- `settings`
- `news`
- `programs`
- `podcasts`
- `team_members`
- `banners`
- `urgent_notices`
- `contact_messages`
- `audit_logs`

Indices:

- `idx_news_status_published_at`
- `idx_programs_active_day_time`
- `idx_contact_messages_status_created`
- `idx_banners_status_dates`
- `idx_urgent_notices_status_dates`
- `idx_audit_logs_user_created`

Relaciones declaradas:

- `users.role_id` -> `roles.id`
- `news.created_by` / `news.updated_by` -> `users.id`
- `podcasts.program_id` -> `programs.id`
- `audit_logs.user_id` -> `users.id`

### HECHO VERIFICADO

No existen tablas para:

- eventos;
- biblioteca multimedia;
- archivos/media assets;
- promociones mixtas independientes de `banners`;
- usuarios avanzados/permisos granulares.

### INFERENCIA

La tabla `podcasts` existe en esquema, pero no se observa CRUD administrativo ni servicio/modelo especifico de podcasts.

## 8. Multimedia, uploads y promociones

### HECHO VERIFICADO

`banners` soporta:

- `title`
- `subtitle`
- `image_path`
- `link_url`
- `position`
- `status`
- `start_at`
- `end_at`
- `display_order`

`BannerService` implementa:

- `allForAdmin()`
- `active()`
- `find()`
- `create()`
- `update()`
- `toggle()`
- `archive()`
- `activeCount()`

`BannerValidator` valida:

- titulo obligatorio;
- posicion permitida;
- estado permitido;
- URL externa de imagen solo si empieza por `http` y debe ser HTTPS;
- link HTTPS;
- rango de fechas.

### HECHO VERIFICADO

No se detecto:

- `move_uploaded_file`;
- uso de `$_FILES`;
- validacion MIME;
- validacion de tamano;
- tabla de archivos;
- reutilizacion de media;
- soporte de video en banners;
- duracion configurable por slide;
- preview tecnica independiente.

### INFERENCIA

El sistema actual de banners es una base util para promociones simples, pero no es una biblioteca multimedia ni un carrusel mixto imagen/video.

## 9. Eventos

### HECHO VERIFICADO

- No existe `EventController`, `EventService`, `Event` model ni tabla `events`.
- `urgent_notices.type` admite valor `event`, pero sigue siendo aviso urgente, no modulo de eventos.

### INFERENCIA

Eventos esta AUSENTE como modulo real. No debe confundirse con avisos urgentes.

## 10. Seguridad

### HECHO VERIFICADO

Controles presentes:

- PDO con prepared statements en modelos.
- `SecurityHelper::e()` para escape HTML.
- `SecurityHelper::csrfToken()` y `verifyCsrfToken()`.
- CSRF en formularios admin y formulario publico de contacto.
- Session cookies `httponly`, `samesite=Lax`, `secure` si HTTPS.
- Rate limiting local por `LoginRateLimiterService`.
- Logger en `storage/logs`.
- Audit log en tabla `audit_logs`.
- `.htaccess` raiz bloquea `.env`, composer, phpunit/phpstan/phpcs y carpetas internas.
- `public/.htaccess` define headers: `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, CSP conservadora.
- `public/uploads/.htaccess` bloquea ejecucion PHP.
- `.env` esta en `.gitignore`.

### HECHO VERIFICADO

Existe archivo local `.env` en el repositorio de trabajo. No se inspecciono ni se imprimio su contenido.

### PENDIENTE DE PRUEBA

- Verificar en Apache/DonWeb real que `.htaccess` y CSP funcionen igual.
- Verificar que CSP no rompa Facebook/Servilive en navegador real.
- Verificar paquete ZIP generado por `scripts/build-deploy.ps1`.

## 11. Sitio publico y UX

### HECHO VERIFICADO

Vistas publicas:

- `resources/views/public/home.php`
- `resources/views/public/live.php`
- `resources/views/public/schedule.php`
- `resources/views/public/news.php`
- `resources/views/public/team.php`
- `resources/views/public/advertising-contact.php`
- `resources/views/public/podcasts.php`
- `resources/views/public/upcoming.php`

Componentes compartidos:

- `header.php`
- `footer.php`
- `floating-player.php`
- `live-video-player.php`
- `facebook-feed.php`
- `public-banner.php`
- `urgent-notice-bar.php`

JS modular:

- `src/scripts/main.js`
- `navigation.js`
- `motion.js`
- `media.js`
- `player.js`

CSS modular:

- `src/styles/main.css`
- `tokens.css`, `base.css`, `layout.css`, `typography.css`, `motion.css`
- componentes y paginas en `src/styles/components` y `src/styles/pages`
- `legacy-public.css` y `legacy-admin.css` siguen existiendo.

### INFERENCIA

La experiencia publica esta orientada a radio/medio digital. Persisten riesgos de duplicacion CSS por convivencia de legacy y CSS modular, pero no se ejecuto inspeccion visual completa en navegador en esta auditoria.

## 12. Pruebas existentes

### HECHO VERIFICADO

Tests detectados:

- Unitarios para helpers, validadores, sesiones, rate limiter, logger, ProgramScheduleService, View.
- Integracion de servicios: Auth, News, Program, Setting, Team, ContactMessage, Banner, UrgentNotice.
- BDD/Gherkin documental para autenticacion, noticias, programas, equipo, contacto y hardening.

### BRECHAS

No se detectan pruebas HTTP end-to-end para:

- rutas admin protegidas;
- CSRF a nivel request real;
- permisos por rol en cada operacion;
- formularios completos;
- uploads;
- preview de banners;
- auditoria de acciones por cada modulo;
- interaccion admin/frontend tras publicar contenido;
- CSP/headers reales en Apache;
- flujo de paquete DonWeb.

## 13. Experiencia administrativa

### HECHO VERIFICADO

Un administrador puede gestionar desde panel:

- Noticias.
- Programacion.
- Equipo.
- Mensajes.
- Configuracion general.
- Banners.
- Avisos urgentes.

### HECHO VERIFICADO

Tareas que todavia requieren edicion manual o no estan resueltas desde admin:

- Subir archivos reales.
- Reemplazar imagenes por biblioteca.
- Gestionar videos/promociones mixtas.
- Gestionar eventos.
- Gestionar usuarios.
- Gestionar podcasts.
- Confirmar o editar algunos assets de marca.
- Gestionar backups/restauracion.

## 14. Limitaciones de la auditoria

- No se modifico codigo funcional.
- No se consulto contenido de `.env`.
- No se realizaron pruebas destructivas contra base de datos.
- `composer test` y `composer analyse` fallaron por lectura de archivos existentes, por lo que sus suites no quedaron verificadas en esta iteracion.
- No se hizo inspeccion visual con navegador real.
