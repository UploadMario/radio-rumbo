# Guia De Trabajo Con Codex

## Arquitectura

Radio Rumbo usa una arquitectura MVC simple sin framework pesado. `public/index.php` actua como entrada publica, `public/admin/index.php` como entrada publica placeholder del panel administrativo y `app/` concentra la logica PHP.

La Fase 1.1 agrega una GUI conectable con layouts, parciales, vistas PHP, logo real y datos mock centralizados. No implementa backend real, autenticación, CRUDs ni conexión dinámica a MySQL.

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
- `config/mock-data.php`: datos base confirmados y placeholders de Fase 1.1 para vistas conectables.
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

## Reglas de carpetas

- No colocar logica de negocio dentro de `public/`.
- Usar `public/` como document root. `/admin/` debe resolver a `public/admin/index.php`.
- Mantener las vistas en `resources/views` y los datos mock en `config/mock-data.php`.
- Escapar datos mock al imprimirlos en HTML.
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
2. Si es una vista visual, crear primero una vista en `resources/views` y datos mock controlados.
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
- En Fase 1, distinguir claramente entre datos mock y funcionalidad real.
- En Fase 1.1, mantener como datos reales confirmados solo `Radio Rumbo`, `La radio que se ve` y `Huancayo, Perú`.
- En Fase 2.0, todo formulario administrativo funcional debe usar CSRF, validación servidor y consultas preparadas.
- En Fase 2.0.1, las URLs externas deben validarse como `https://` antes de imprimirse o usarse en iframes/audio.
- Sin frameworks pesados.
- Sin credenciales reales.
- Roles sin cambios no autorizados.
- Pruebas y herramientas ejecutadas o limitacion documentada.
- Codigo legible, tipado y alineado a PSR-12.
