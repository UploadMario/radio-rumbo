# Guia De Trabajo Con Codex

## Arquitectura

Radio Rumbo usa una arquitectura MVC simple sin framework pesado. `public/index.php` actua como entrada publica, `public/admin/index.php` como entrada publica placeholder del panel administrativo y `app/` concentra la logica PHP.

La Fase 1.1 agregó una GUI conectable con layouts, parciales, vistas PHP, logo real y contenido base centralizado. Las fases 2.x ya incorporan backend real, autenticación y CRUDs principales.

La Fase 2.0 agrega backend mínimo real: autenticación administrativa, sesiones seguras, lectura de settings y CRUD mínimo de noticias. Se mantiene la arquitectura simple y compatible con DonWeb.

La Fase 2.0.1 agrega credenciales locales documentadas, script local idempotente y enlaces reales de audio, video y Facebook. No agrega módulos funcionales nuevos.

Capas principales:

- `app/Core`: infraestructura minima como router, base de datos y vistas.
- `app/Controllers`: controladores HTTP.
- `app/Models`: representacion de entidades y acceso futuro a datos.
- `app/Services`: reglas de negocio.
- `app/Middleware`: autenticacion, roles y validaciones transversales.
- `app/Validators`: validaciones de entrada.
- `app/Helpers`: utilidades puras.
- `config`: configuracion desacoplada de codigo.
- `config/default-content.php`: contenido base oficial y fallbacks seguros para vistas conectables.
- `database`: SQL versionable y migraciones futuras.
- `resources/views`: layouts, parciales y vistas PHP fuera del document root.
- `tests`: pruebas unitarias, integracion y BDD documental.

## Convenciones

- PHP con `declare(strict_types=1);`.
- Namespaces PSR-4 bajo `App\`.
- Clases en PascalCase, metodos en camelCase.
- Salida HTML escapada por defecto.
- Sin credenciales reales en codigo, seeds ni documentacion.
- Roles administrativos limitados a `Super Admin` y `Admin`.

## Comandos

```bash
composer install
composer test
composer analyse
composer lint
php -S localhost:8000 -t public
```

Extensiones PHP recomendadas: `pdo_mysql`, `mbstring`, `dom`, `xml`, `xmlwriter`, `SimpleXML` e `iconv`.

## Fase 2.1

- Programacion: rutas publicas `/programacion/` y admin `/admin/programs/`.
- Equipo: rutas publicas `/equipo/` y admin `/admin/team/`.
- Contacto/publicidad: formulario publico `/publicidad-contacto/` y admin `/admin/messages/`.
- Todos los formularios administrativos usan CSRF; contacto publico usa CSRF y honeypot.
- No hay envio de correos, uploads reales, podcasts funcionales, ranking, banners avanzados ni roles nuevos.
- Para bases existentes revisar y aplicar `database/migrations/2026_08_04_phase_2_1_programs_team_messages.sql`.

## Fase 2.2

- Configuracion: `/admin/settings/`, editable solo por `Super Admin`.
- Banners: `/admin/banners/`, `/admin/banners/create/`, `/admin/banners/edit/?id=ID`.
- Avisos urgentes: `/admin/urgent-notices/`, `/admin/urgent-notices/create/`, `/admin/urgent-notices/edit/?id=ID`.
- Banners y avisos usan estados `draft`, `active`, `inactive`, `archived` y fechas opcionales.
- No implementar uploads reales; `image_path` debe ser placeholder, ruta manual o URL HTTPS.
- `.env` permanece como fallback de configuracion.

## Fase 2.6

- Pulido visual y de mensajes para presentacion ejecutiva.
- Mantener los modulos funcionales actuales sin agregar funcionalidades grandes.
- Usar lenguaje publico profesional: `Contenido pendiente de confirmacion`, `Disponible para carga administrativa` y `Listo para completar con informacion oficial`.
- El dashboard debe explicar modulos implementados, estado de presentacion y pendientes antes del despliegue final.
- Los modulos pendientes deben mostrarse como `Proxima fase`, `Planificado` o `No incluido en esta version de presentacion`.

## Reglas de carpetas

- No colocar logica de negocio dentro de `public/`.
- Usar `public/` como document root. `/admin/` debe resolver a `public/admin/index.php`.
- Mantener las vistas en `resources/views` y el contenido base en `config/default-content.php`.
- Escapar contenido externo o configurable al imprimirlo en HTML.
- No colocar configuracion sensible dentro de assets.
- No exponer `app/`, `config/`, `database/`, `docs/`, `resources/`, `tests/`, `vendor/` ni `.env`.
- Guardar archivos subidos solo en `public/uploads/` con validaciones futuras.
- Documentar decisiones tecnicas en `docs/architecture/`.
- Documentar riesgos en `docs/security/`.
- Documentar pruebas y criterios de aceptacion en `docs/qa/`.

## Estandares de codigo

- PSR-12 mediante PHPCS.
- Analisis estatico con PHPStan nivel 6 inicial.
- Dependencias minimas y justificadas.
- Preferir PHP nativo y PDO antes que librerias innecesarias.

## Seguridad

- PDO con excepciones y charset `utf8mb4`.
- Consultas preparadas como regla obligatoria.
- `password_hash()` para contrasenas futuras.
- CSRF para formularios administrativos.
- Escape HTML para toda salida no confiable.
- `public/uploads/.htaccess` debe bloquear ejecucion PHP, sin reemplazar validaciones de codigo futuras.
- Validacion de extensiones, MIME, tamano y nombre para uploads futuros.
- Auditoria futura de acciones administrativas en `audit_logs`.

## Despliegue DonWeb

- Configurar el document root hacia `public/`.
- Si el hosting no lo permite, publicar solo el contenido de `public/` como area web y ubicar el resto fuera del directorio publico cuando sea posible.
- Crear la base de datos desde el panel DonWeb.
- Importar `database/schema.sql` sobre la base ya creada.
- Crear `.env` real en el hosting a partir de `.env.example`; nunca versionarlo.
- Ejecutar Composer en hosting si esta disponible. Si no, subir `vendor/` como artefacto sin exponerlo publicamente.
- Revisar `docs/deployment/donweb.md` antes de publicar.

## Proceso de QA

1. Confirmar requisito.
2. Agregar o actualizar pruebas cuando aplique.
3. Ejecutar `composer test`.
4. Ejecutar `composer analyse`.
5. Ejecutar `composer lint`.
6. Revisar que no haya credenciales ni datos reales.
7. Actualizar documentacion si cambia arquitectura, seguridad o alcance.

## Como crear nuevos modulos

1. Definir requisito en `docs/requirements/requirements.md`.
2. Si es una vista visual, crear primero una vista en `resources/views` y contenido base controlado.
3. Crear controlador en `app/Controllers` cuando ya exista comportamiento backend.
4. Crear modelo si hay persistencia.
5. Crear servicio si hay reglas de negocio.
6. Agregar validadores para entrada.
7. Usar middleware si requiere autenticacion o rol.
8. Crear pruebas unitarias o de integracion.
9. Documentar rutas, riesgos y criterios de aceptacion.

## Como documentar cambios

- Cambios funcionales: actualizar requisitos.
- Cambios de arquitectura: actualizar `docs/architecture/architecture.md`.
- Cambios de seguridad: actualizar matriz de riesgos.
- Cambios de pruebas: actualizar estrategia o Definition of Done si aplica.

## Criterios minimos para aceptar una tarea

- Requisito implementado sin sobredimensionar.
- Compatible con PHP 8.1+ y hosting compartido DonWeb.
- `/` y `/admin/` funcionan con `php -S localhost:8000 -t public`.
- Distinguir claramente entre contenido base, configuración real y datos persistidos.
- En Fase 1.1, mantener como datos reales confirmados solo `Radio Rumbo`, `La radio que se ve` y `Huancayo, Perú`.
- En Fase 2.0, todo formulario administrativo funcional debe usar CSRF, validación servidor y consultas preparadas.
- En Fase 2.0.1, las URLs externas deben validarse como `https://` antes de imprimirse o usarse en iframes/audio.
- Sin frameworks pesados.
- Sin credenciales reales.
- Roles sin cambios no autorizados.
- Pruebas y herramientas ejecutadas o limitacion documentada.
- Codigo legible, tipado y alineado a PSR-12.

## Fase 3.0 Hardening

- Configurar timezone con `APP_TIMEZONE`; si falta o es inválido se usa `America/Lima`.
- `SessionService` aplica timeout administrativo por inactividad con `ADMIN_SESSION_TIMEOUT`.
- `LoginRateLimiterService` usa archivos en `storage/rate-limit/`, compatible con hosting compartido y sin Redis.
- Logout administrativo debe ser `POST` con CSRF. No usar enlaces GET para cerrar sesión.
- `LoggerService` escribe en `storage/logs/app.log` y sanitiza contraseñas, tokens, cookies y secretos.
- `AuditLogService` registra acciones administrativas importantes en `audit_logs` sin romper la operación si falla.
- La programación actual/siguiente vive en `ProgramScheduleService`; no devolver esa lógica a `public/bootstrap.php`.
- Usar `config/default-content.php` para contenido base oficial. `config/mock-data.php` solo existe como alias temporal de compatibilidad.
- Para despliegue DonWeb usar `scripts/build-deploy.ps1` y crear `.env` de producción manualmente en el servidor.

## Integracion Facebook

Facebook debe mostrarse con el Page Plugin oficial dentro de la web. Usar primero `facebook_page_plugin_url` desde BD, luego `FACEBOOK_PAGE_PLUGIN_URL`; si ambos estan vacios, construir la URL del plugin desde `facebook_page_url` o `FACEBOOK_PAGE_URL` validada por HTTPS. No usar sandbox en el iframe, scraping, SDK obligatorio ni API privada.

## Contenido oficial publico

La web publica usa como base oficial: Radio Rumbo desde 1996, lema `La radio que se ve`, `91.1 FM`, `1560 AM`, sede `Jr. Ayacucho 297, Huancayo, Junin, Peru`, telefono comercial `964436961`, transmision online/video y redes oficiales recibidas. No publicar programacion, conductores, equipo, banners ni correo institucional como datos definitivos hasta que el cliente los confirme.
